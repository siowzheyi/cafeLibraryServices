<?php
  
namespace App\Http\Controllers\Auth;
  
use App\Http\Controllers\Controller as Controller;
use App\Http\Controllers\API\BaseController as BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use App\Models\Library;
use App\Models\Cafe;

use Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
  
class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(): View
    {
        return view('auth.login');
    }  
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration(): View
    {
        return view('auth.registration');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        // dd($request);
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if($user->hasRole('staff'))
            {
                if($user->library_id != null || $user->cafe_id != null)
                    return redirect('dashboard');
                else
                    return redirect('library_cafe');
            }
            elseif($user->hasRole('admin'))
                return redirect('dashboard');
            else
                return redirect("login")->withSuccess('Oppes! Please use mobile to login');
        }
        return redirect("login")->withErrors('Oppes! You have entered invalid credentials');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request): RedirectResponse
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        if(Auth::check()){
            $user = auth()->user();
            if($user->hasRole('admin'))
                return view('dashboard.index');
            elseif($user->cafe_id != null)
                return view('dashboard.cafe');
            elseif($user->library_id != null)
                return view('dashboard.library');
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }

    public function libraryCafe()
    {
        $data = array();
        // get list of library and return
        $libraries = Library::where('status',1)->get();
        foreach($libraries as $library)
        {
            $cafe = Cafe::where('library_id',$library->id)->first();
            if($cafe == null)
                array_push($data, $library);
        }
        
        return view('auth.create_cafe_or_library',compact('data'));
    }

    public function createLibraryCafe(Request $request)
    {
        $type = $request->entityType;
        if($type == "cafe")
        {
            $cafe = new Cafe();
            $cafe->name = $request->name;
            $cafe->library_id = $request->library;
            $cafe->save();
    
            $user = auth()->user();
    
            $user->cafe_id = $cafe->id;
            $user->save();
            return view('dashboard.cafe');

        }
        else
        {
            $library = new Library();
            $library->name = $request->name;
            $library->address = $request->address;
            $library->save();
    
            $user = auth()->user();
    
            $user->library_id = $library->id;
            $user->save();
            return view('dashboard.library');

        }

        
    }
}