<!-- resources/views/admin/services/epan_print.blade.php -->
<div id="panCardPrintAreaV2" class="d-none">
    <style>
        .epan-v2-body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            box-sizing: border-box;
        }
        .epan-v2-container {
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        .epan-v2-header-bar {
            background: linear-gradient(90deg, #1a237e, #0d47a1);
            color: white;
            padding: 15px 25px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .epan-v2-pan-viewer {
            padding: 20px;
            text-align: center;
            background: #fafafa;
        }
        .epan-v2-pan-viewer img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .epan-v2-download-section {
            padding: 30px 25px;
            text-align: center;
            background: white;
            border-top: 1px solid #eee;
        }
        .epan-v2-download-section h3 {
            color: #1a237e;
            margin-bottom: 10px;
            font-size: 1.3rem;
        }
        .epan-v2-download-section p {
            color: #555;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        .epan-v2-download-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #1565c0, #0d47a1);
            color: white;
            border: none;
            padding: 14px 32px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(13, 71, 161, 0.3);
            text-decoration: none;
        }
        .epan-v2-download-btn:hover {
            background: linear-gradient(135deg, #0d47a1, #002171);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 71, 161, 0.4);
        }
        .epan-v2-download-btn:active {
            transform: translateY(0);
        }
        .epan-v2-download-btn svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
        }
        .epan-v2-info-box {
            margin-top: 25px;
            padding: 15px 20px;
            background: #e3f2fd;
            border-left: 4px solid #1565c0;
            border-radius: 4px;
            text-align: left;
            font-size: 0.9rem;
            color: #333;
        }
        .epan-v2-info-box strong {
            color: #0d47a1;
        }
        @media print { 
            body { 
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
            } 
            .epan-v2-download-btn { display: none !important; }
        }
    </style>
    
    <div class="epan-v2-body">
        <div class="epan-v2-container">
            <div class="epan-v2-header-bar">
                आयकर विभाग | INCOME TAX DEPARTMENT — e-PAN Card Viewer
            </div>
            
            <div class="epan-v2-pan-viewer">
                <!-- Dynamic data will be injected here via JavaScript -->
                <img src="{{ asset('dummy_epan.png') }}" alt="e-PAN Card" id="epanPrintImageV2">
            </div>
            
            <div class="epan-v2-download-section">
                <h3>Your e-PAN Card is Ready</h3>
                <p>This is a digitally verified e-PAN card fetched from official records. You can download or print it for your use.</p>
                
                <a href="#" class="epan-v2-download-btn" onclick="window.print(); return false;">
                    <svg viewBox="0 0 24 24">
                        <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                    </svg>
                    Print e-PAN Card
                </a>
                
                <div class="epan-v2-info-box">
                    <strong>PAN Details:</strong><br>
                    Name: <span id="epanPrintNameV2">ILMA</span><br>
                    Father's Name: <span id="epanPrintFatherNameV2">NAEEM AHMED</span><br>
                    Date of Birth: <span id="epanPrintDobV2">31/01/2006</span><br>
                    Gender: <span id="epanPrintGenderV2">Female</span><br>
                    Permanent Account Number: <strong id="epanPrintPanNumberV2">ARMPI4787M</strong><br>
                    <br>
                    <em>This is an electronically issued and digitally signed e-PAN. It is a valid mode of issue of Permanent Account Number (PAN).</em>
                </div>

                <div class="epan-v2-info-box mt-3" style="margin-top: 15px;">
                    <strong>Note:</strong> This e-PAN is a valid proof of identity and Permanent Account Number. It is digitally signed and carries the same validity as a physical PAN card under the IT Act.
                </div>
            </div>
        </div>
    </div>
</div>
