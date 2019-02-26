<?php

namespace App\Http\Controllers;
use App\Profile;
use App\User;
use App\Country;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use PDF;
//require_once __DIR__ . '/vendor/autoload.php';
class ProfilsController extends Controller
{

    public function getProfile (Request $request, $id)
    {
        $profile = Profile::where('user_id',$id)
             ->first();
        return response()
             ->json($profile, 200);
    }


    public function removeProfile (Request $request, $id)
    {
        $user = Auth::user();
        $profile = Profile::find($id);
        $profile->delete();
        $user->delete();
        $request->user()->token()->revoke();
        return response()->json([
            "message" => "User delete successfully"
        ],200);

    }

    public function updateProfile (Request $request, $id)
    {
        $credential = [
            'email'=> $request['data']['email'],
            'password' =>$request['password']
        ];
        $validate = Validator::make($credential,[
            'email' => 'string|email|required',
//            'password' => 'string|required',
        ]);
        $user = User::find($id);
//        $password = Hash::make($request['password']);
//        if(!Hash::check($request['password'], $user->password)){
//            return response()->json(['message' => 'Incorrect password'],401);
//        };

        if(!$validate->fails()){
            $user = Auth::user();
            $profile = Profile::where('user_id',$id)->first();
            $data_profile = $request->all()['data'];
            array_splice( $data_profile, 15);
            $data_user =[$data_profile['email'], $data_profile['nickname']];
//            $pattern = '^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$';
//
//            $number_pattern = preg_match($pattern, $request->get('number'));
//            if($number_pattern == 0 && $request->get('number') != null){
//                return response()->json([
//                    "message" => 'Wrong number'
//                ]);
//            }

            if ( $user->update($data_user)) {
                $profile->update($data_profile);
                return response()->json([
                    'message' => 'Successfully updated user!',
                    "profile" => $profile
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Something went wrong on update user',
                    'user' => $user
                ], 500);
            }
        } else {
            return response()->json([
                $validate->errors()
            ], 500);
        }
    }
    public function fileUpload(Request $request){
        $unix_timestamp_name = now()->timestamp.str_random(24);
        $server = "http://127.0.0.1:8000";
        $isHttps = !empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']);
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $validate = Validator::make(["image"=> $file->getSize()],[
                'image' => 'max:4000000',
            ]);
            if($validate->fails()){
                return response()->json($validate->errors(), 400);
            };
//            dd($_SERVER);
//            dd(public_path());
            $file->move(public_path().'/images',
                $unix_timestamp_name.".png");
            return response()->json([
                'message' => 'upload successfully',
                'link'    => $server.'/images/'.$unix_timestamp_name.".png",
            ]);
        } else {
            return response()->json([
                'message' => 'file isn`t image',
            ], 400);
        }
    }

    public function getCountry(Request $request){
        $country = Country::where('def', 1)
            ->join('country_flags', 'short', '=', 'country_flags.iso')
            ->join('CountryPhoneCods', 'full_eng', '=', 'CountryPhoneCods.TitleEN')
            ->select('full_eng', 'iso', 'flag_uri', 'PhoneCode')
            ->get();
        return response()->json(['data' => $country], 200);
    }
    public function pdfCr(Request $request, $id){
        $filename = md5(time());
        function base64_to_jpeg( $base64_string, $output_file ) {
            $ifp = fopen( $output_file, "wb" );
            $data = explode( ',', $base64_string );

            fwrite( $ifp, base64_decode( $data[1]) );
            fclose( $ifp );
            return( $output_file );
        }
            $image1 = base64_to_jpeg( $request->all()['image'], $request->all()['count'].$id.'.png' );
        return response()->json([
            'id' => $id.'.png',
            "img"=> $image1
        ]);
    }
    public function pdf(Request $request, $id){
//        header('Content-type: application/pdf');
//        header('Content-disposition: attachment; filename=some.pdf');

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
//dd(__DIR__ . '/storage/fonts');
        $mpdf = new \Mpdf\Mpdf(

            [
            'fontDir' => array_merge($fontDirs, [
              '/home/cubex/all_project/api/storage/fonts',
            ]),
            'fontdata' => $fontData + [
                    'taprom' => [
                        'R' => 'Taprom.ttf',
                    ],
                    'montserrat' => [
                        'R' => 'Montserrat-Regular.ttf',
                    ]
                ],
            'default_font' => 'montserrat'
        ]

        );
//        $mpdf

        function get_image($name){
            $im = file_get_contents($name.'.png');
            $siz3e = getimagesize($name.'.png');
            $image = base64_encode($im);
            return 'data:'.$siz3e['mime'].';base64,'.$image;
        }
//        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML(view('p1'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p2'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p3'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p4'));

        $mpdf->AddPage();


        $data1 = get_image('1'.$id);
        $mpdf->WriteHTML(view('p5', compact('data1')));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p6'));

        $data2 = get_image('2'.$id);
        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p7', compact('data2')));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p8'));

//        $mpdf->AddPage();
//        $mpdf->WriteHTML(view('p9_horizontal'));

        $data3 = get_image('3'.$id);
        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p9_reports', compact('data3')));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p10'));

        $data4 = get_image('4'.$id);
        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p11', compact('data4')));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p12'));

        $data5 = get_image('5'.$id);
        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p13', compact('data5')));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p14'));

        $data6 = get_image('6'.$id);
        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p15', compact('data6')));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p16'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p17'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p18'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p19'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p20'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p21'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p22'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p23'));

        $mpdf->AddPage();
        $width = '120px';
        $mpdf->WriteHTML(view('p24', compact('width')));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p25'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p26'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p27'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p28'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p29'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p30'));

        $mpdf->AddPage();
        $mpdf->WriteHTML(view('p31'));
        $mpdf->Output();

//'welcome.pdf', \Mpdf\Output\Destination::DOWNLOAD
//        return $pdf->download('welcome.pdf');
    }
    public function diagrams(Request $request, $id_unicum){
        $one = '1,2,3,4,5, 100';
        $two = '60,30,80,19,70,10';
        $three = '30,90,80,9,10,70';
        $four = '20,90,40,29,60,40';
        $five = '10,90,20,39,80,40';
        $six = '40,30,50,29,70,30';
        return view('first_page', compact('id_unicum', 'one', 'two', 'three', 'four', 'five', 'six'));
    }
}

