<?php

namespace App\Http\Controllers;

use App\Mahasiswa;
use Illuminate\Http\Request;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class QRController extends Controller
{
    public function __construct(QrCode $qrCode)
    {
        $this->qrCode = $qrCode;
    }

    public function index()
    {
        return view('qr');
    }

    public function create($text)
    {
        $qrCode = new QrCode($text);
		$qrCode->setSize(300);
		$qrCode->setMargin(10);
		$qrCode->setEncoding('UTF-8');
		$qrCode->setWriterByName('png');
		$qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
		$qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
		$qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setLogoSize(170, 75);
        $qrCode->setLogoPath('logo_ifj.png');
		$qrCode->setValidateResult(false);
		$qrCode->setRoundBlockSize(true);
		$qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
		header('Content-Type: '.$qrCode->getContentType());
		$qrCode->writeFile(public_path('qrcodes/qrcode-'.$text.'.png'));

		// return redirect()->route('qrcode.index');
    }

    public function generateQRCode($nrp = "all")
    {
        if($nrp == "all"){
            $data = Mahasiswa::all();
            foreach($data as $mhs){
                if($mhs->qr_code == null){
                    $this->create($mhs->nrp);
                    $mhs->qr_code = 'qrcodes/qrcode-'.$mhs->nrp.'.png';
                    $mhs->save();
                }
            }
        }else{
            // dd('a');
            $mhs = Mahasiswa::find($nrp);
            // dd($mhs->qr_code);
            if($mhs->qr_code == null){
                // dd('a');
                $this->create($mhs->nrp);
                $mhs->qr_code = 'qrcodes/qrcode-'.$mhs->nrp.'.png';
                $mhs->save();
            }
        }
        return back();
    }

    public function check($nrp)
    {
        $mhs = Mahasiswa::find($nrp);
        if($mhs == null){
            return null;
        }
        return $mhs->qr_code;
    }
}
