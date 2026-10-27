<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IdspayClient
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $secretKey;
    protected int $timeout;
    protected bool $mockMode;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('idspay.base_url', 'https://apiuser.idspay.in'), '/');
        $this->apiKey = config('idspay.api_key', '');
        $this->secretKey = config('idspay.secret_key', '');
        $this->timeout = config('idspay.timeout', 30);
        $this->mockMode = config('idspay.mock_mode', false);
    }

    /**
     * Dispatch an API request to the IDSPay platform.
     *
     * @param string $endpointPath
     * @param string $method
     * @param array $payload
     * @return array
     */
    public function dispatch(string $endpointPath, string $method = 'POST', array $payload = []): array
    {
        $startTime = microtime(true);
        if (str_starts_with($endpointPath, 'http://') || str_starts_with($endpointPath, 'https://')) {
            $fullUrl = $endpointPath;
            $cleanPath = parse_url($endpointPath, PHP_URL_PATH) ?? $endpointPath;
        } else {
            $cleanPath = '/' . ltrim($endpointPath, '/');
            // If path starts with /srv1/ or /srv2/ and baseUrl does not already contain /api/v1/uat, prepend /api/v1/uat
            if ((str_starts_with($cleanPath, '/srv1/') || str_starts_with($cleanPath, '/srv2/')) && !str_contains($this->baseUrl, '/api/v1/uat')) {
                $fullUrl = $this->baseUrl . '/api/v1/uat' . $cleanPath;
            } else {
                $fullUrl = $this->baseUrl . $cleanPath;
            }
        }

        // Normalize any duplicate slashes in the URL path (e.g. /uat//srv2/ -> /uat/srv2/)
        $parsed = parse_url($fullUrl);
        if ($parsed && isset($parsed['scheme']) && isset($parsed['host'])) {
            $normalizedPath = preg_replace('#/{2,}#', '/', $parsed['path'] ?? '');
            $fullUrl = $parsed['scheme'] . '://' . $parsed['host'] . (isset($parsed['port']) ? ':' . $parsed['port'] : '') . $normalizedPath;
            if (isset($parsed['query'])) {
                $fullUrl .= '?' . $parsed['query'];
            }
            $cleanPath = $normalizedPath;
        }

        // If mock mode is enabled or in simulation environment
        if ($this->mockMode && empty($this->apiKey)) {
            $executionMs = (int) round((microtime(true) - $startTime) * 1000);

            $mockDetails = [
                'transaction_id' => 'IDSPAY_MOCK_' . strtoupper(uniqid()),
                'timestamp' => now()->toIso8601String(),
                'verified' => true,
            ];

            // Provide realistic data for the 6 IDSPay integrated services
            if (str_contains($cleanPath, 'pan') || str_contains($cleanPath, 'aadhar')) {
                $mockPan = $payload['pan'] ?? (!empty($payload['aadhar']) ? 'GENPB6869D' : ('ABCDE' . rand(1000, 9999) . 'F'));
                $mockDetails['pan_number'] = $mockPan;
                $mockDetails['pan'] = $mockPan;
                $mockDetails['linked_pan'] = $mockPan;
                $mockDetails['name_on_pan'] = $payload['name'] ?? 'RAJESH KUMAR SHARMA';
                $mockDetails['name'] = $payload['name'] ?? 'RAJESH KUMAR SHARMA';
                $mockDetails['fullname'] = $payload['name'] ?? 'RAJESH KUMAR SHARMA';
                $mockDetails['father_name'] = 'SURESH SHARMA';
                $mockDetails['pan_status'] = 'Active & Linked';
                $mockDetails['aadhaar_linked'] = true;
                if (isset($payload['name_match_method'])) {
                    $mockDetails['name_match_method'] = $payload['name_match_method'];
                    $mockDetails['name_match_score'] = '98.5%';
                }
            } elseif (str_contains($cleanPath, 'rc') || str_contains($cleanPath, 'vahan') || str_contains($cleanPath, 'vehicle')) {
                $mockDetails['vehicle_num'] = $payload['vehicle_num'] ?? 'GJ08BX1234';
                $mockDetails['owner_name'] = 'AMIT VERMA';
                $mockDetails['maker_model'] = 'HYUNDAI CRETA SX';
                $mockDetails['fuel_type'] = 'PETROL';
                $mockDetails['mobile'] = '98XXXXXX12';
                $mockDetails['registration_date'] = '2021-04-12';
            } elseif (str_contains($cleanPath, 'voter') || str_contains($cleanPath, 'epic')) {
                $mockDetails['voterId'] = $payload['voterId'] ?? 'ABC1234567';
                $mockDetails['name'] = 'PRIYA SHARMA';
                $mockDetails['constituency'] = 'NEW DELHI';
                $mockDetails['polling_station'] = 'Govt Senior Secondary School, Booth #42';
            } elseif (str_contains($cleanPath, 'dl')) {
                $mockDl = $payload['dlNumber'] ?? $payload['dl_number'] ?? 'DL-1420110012345';
                $mockDetails['dlNumber'] = $mockDl;
                $mockDetails['dl_number'] = $mockDl;
                $mockDetails['dob'] = $payload['dob'] ?? '1990-05-15';
                $mockDetails['holder_name'] = 'AMXXXXXJ R';
                $mockDetails['name'] = 'AMXXXXXJ R';
                $mockDetails['validity'] = '2036-09-24';
                $mockDetails['expiry_date'] = '2036-09-24';
                $mockDetails['cov'] = 'MCWOG';
                $mockDetails['details_of_driving_licence'] = [
                    'name' => 'AMXXXXXJ R',
                    'father_or_husband_name' => 'HUXXXIN',
                    'status' => 'Active',
                    'expiry_date' => '24-09-2036',
                    'date_of_issue' => '25-09-2026',
                    'user_blood_group' => 'O+',
                    'user_image' => '',
                    'address' => '24/44 IBRAHIM STREET, VELLORE DT, TN 632602',
                    'address_list' => [
                        [
                            'complete_address' => '24/44 IBRAHIM STREET, VELLORE DT, TN 632602',
                            'type' => 'permanent',
                        ],
                    ],
                ];
                $mockDetails['vehicle_category_details'] = [
                    [
                        'cov' => 'MCWOG',
                        'cov_type' => 'non-transport',
                        'issue_date' => '25-09-2026',
                        'expiry_date' => '24-09-2036',
                    ],
                    [
                        'cov' => 'LMV',
                        'cov_type' => 'non-transport',
                        'issue_date' => '25-09-2026',
                        'expiry_date' => '24-09-2036',
                    ],
                ];
            }

            return [
                'success' => true,
                'status' => 'SUCCESS',
                'http_code' => 200,
                'data' => [
                    'status' => 'SUCCESS',
                    'response_code' => '00',
                    'message' => 'Operation completed successfully (Mock Mode)',
                    'data' => array_merge($payload, $mockDetails),
                ],
                'error' => null,
                'execution_time_ms' => max(1, $executionMs),
            ];
        }

        try {
            $effectiveApiId = $payload['api_id'] ?? config('idspay.api_id', 'APID3597');
            $effectiveApiKey = $payload['api_key'] ?? $this->apiKey;
            $effectiveTokenId = $payload['token_id'] ?? config('idspay.token_id', 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN');

            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];

            if (!empty($effectiveApiKey)) {
                $headers['X-API-KEY'] = $effectiveApiKey;
                $headers['Authorization'] = 'Bearer ' . $effectiveApiKey;
            }

            if (!empty($this->secretKey)) {
                $headers['X-SECRET-KEY'] = $this->secretKey;
            }

            // Auto-enrich upstream body with API credentials if configured in gateway or defaults
            if (!empty($effectiveApiId)) {
                $payload['api_id'] = $effectiveApiId;
            }
            if (!empty($effectiveApiKey)) {
                $payload['api_key'] = $effectiveApiKey;
            }
            if (!empty($effectiveTokenId)) {
                $payload['token_id'] = $effectiveTokenId;
            }

            // If calling /srv2/validation/pan (Pan Details V2), ensure default parameters if omitted
            if (str_contains($cleanPath, 'srv2/validation/pan') && !str_contains($cleanPath, 'father-details')) {
                if (!isset($payload['pan_display_name'])) {
                    $payload['pan_display_name'] = 'false';
                }
                if (!isset($payload['name_match_method'])) {
                    $payload['name_match_method'] = 'fuzzy';
                }
            }

            $httpClient = Http::withHeaders($headers)
                ->timeout($this->timeout);

            $response = match (strtoupper($method)) {
                'GET' => $httpClient->get($fullUrl, $payload),
                'PUT' => $httpClient->put($fullUrl, $payload),
                default => $httpClient->post($fullUrl, $payload),
            };

            $executionMs = (int) round((microtime(true) - $startTime) * 1000);
            $statusCode = $response->status();
            $responseData = $response->json();

            // Handle non-JSON response string
            if ($responseData === null) {
                $responseData = [
                    'status' => 'FAILED',
                    'message' => 'Non-JSON response from upstream IDSPay service',
                    'raw_body' => substr($response->body(), 0, 1000),
                ];
            }

            // Check if response indicates success
            // IDSPay convention: status = "SUCCESS", status.type = "success", status.code = 200, or response_code = "00"/200
            $isSuccess = false;
            $statusMessage = null;

            if (isset($responseData['status'])) {
                $statusVal = $responseData['status'];
                if (is_string($statusVal) && in_array(strtoupper(trim($statusVal)), ['SUCCESS', 'SUCCESSFUL', 'TRUE', '1'])) {
                    $isSuccess = true;
                } elseif (is_bool($statusVal) && $statusVal === true) {
                    $isSuccess = true;
                } elseif (is_array($statusVal)) {
                    $type = strtolower($statusVal['type'] ?? '');
                    $code = $statusVal['code'] ?? null;
                    $statusMessage = $statusVal['message'] ?? null;
                    if ($type === 'success' || in_array($code, [200, '200', '00'])) {
                        $isSuccess = true;
                    }
                }
            }

            if (!$isSuccess && $response->successful()) {
                if (isset($responseData['response_code']) && in_array($responseData['response_code'], ['00', '200', 200])) {
                    $isSuccess = true;
                } elseif (isset($responseData['code']) && in_array($responseData['code'], [200, '200', '00'])) {
                    $isSuccess = true;
                } elseif (isset($responseData['success']) && ($responseData['success'] === true || $responseData['success'] === 'true')) {
                    $isSuccess = true;
                } elseif (isset($responseData['data']) && !empty($responseData['data']) && empty($responseData['error'])) {
                    $isSuccess = true;
                }
            }

            $errorMessage = $isSuccess ? null : ($responseData['message'] ?? $statusMessage ?? 'IDSPay returned unsuccessful status');

            return [
                'success' => $isSuccess,
                'status' => $isSuccess ? 'SUCCESS' : 'FAILED',
                'http_code' => $statusCode,
                'data' => $responseData,
                'error' => $errorMessage,
                'execution_time_ms' => $executionMs,
            ];

        } catch (ConnectionException $e) {
            $executionMs = (int) round((microtime(true) - $startTime) * 1000);
            Log::error("IDSPay API Connection/Timeout Exception: " . $e->getMessage(), [
                'url' => $fullUrl,
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'status' => 'TIMEOUT',
                'http_code' => 504,
                'data' => [
                    'status' => 'TIMEOUT',
                    'message' => 'Gateway timeout or connection refused from IDSPay upstream server',
                ],
                'error' => $e->getMessage(),
                'execution_time_ms' => $executionMs,
            ];

        } catch (\Throwable $e) {
            $executionMs = (int) round((microtime(true) - $startTime) * 1000);
            Log::error("IDSPay API Execution Exception: " . $e->getMessage(), [
                'url' => $fullUrl,
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'status' => 'ERROR',
                'http_code' => 500,
                'data' => [
                    'status' => 'ERROR',
                    'message' => 'Internal error communicating with IDSPay: ' . $e->getMessage(),
                ],
                'error' => $e->getMessage(),
                'execution_time_ms' => $executionMs,
            ];
        }
    }
}
