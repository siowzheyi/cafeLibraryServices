import 'package:cafe_library_services/Welcome/select_library.dart';
import 'package:cafe_library_services/main.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:cafe_library_services/Welcome/registration.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import '../Controller/connection.dart';

void main() {
  runApp(MaterialApp(
    theme: ThemeData(
      primarySwatch: Colors.green,
    ),
    debugShowCheckedModeBanner: false,
    home: const LoginPage(),
  ));
}

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {

  var formKey = GlobalKey<FormState>();
  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();

  Future<void> loginUser(String email, String password) async {
    var headers = {
      'Content-Type': 'application/json',
    };

    var request = http.Request(
      'POST',
      Uri.parse(API.login),
    );

    request.body = json.encode({
      'email': email,
      'password': password,
    });

    request.headers.addAll(headers);

    try {
      http.StreamedResponse response = await request.send();

      if (response.statusCode == 200) {
        String responseBody = await response.stream.bytesToString();
        print('Response: $responseBody');

        final decodedResponse = jsonDecode(responseBody);
        if (decodedResponse.containsKey('data') &&
            decodedResponse['data'].containsKey('token') &&
            decodedResponse['data'].containsKey('id')) {
          String token = decodedResponse['data']['token'];
          int userId = decodedResponse['data']['id'];

          // Save the token and user ID securely using SharedPreferences
          await _saveToken(token);
          await _saveUserId(userId);

          // Navigate to the next screen
          _navigateToNextScreen();
        } else {
          print('Token or ID field is missing in the response');
          throw Exception('Token or ID field is missing in the response');
        }
      } else {
        print('HTTP request failed with status code: ${response.statusCode}');
        throw Exception(
            'HTTP request failed with status code: ${response.statusCode}');
      }
    } catch (e) {
      print('An error occurred during login: $e');
      throw Exception('An error occurred during login: $e');
    }
  }

  Future<void> _saveToken(String token) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString('token', token);
  }

  Future<String?> _getToken() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  Future<void> _saveUserId(int userId) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString('userId', userId.toString());
  }

  Future<String?> _getUserId() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString('userId');
  }

  void _navigateToNextScreen() async {
    String? token = await _getToken();
    if (token != null) {
      // You can use the retrieved token to perform any necessary actions
      // or navigate to the next screen.
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(
          builder: (context) => LibrarySelectionPage(),
        ),
      );
    } else {
      print('Token not found in SharedPreferences');
    }
  }

  void _loginUser() async {
    if (emailController.text.isNotEmpty &&
        passwordController.text.isNotEmpty) {
      try {
        await loginUser(emailController.text, passwordController.text);
        // Handle the response or perform additional actions if needed
      } catch (e) {
        print('Error during login: $e');
        Fluttertoast.showToast(msg: 'An error occurred during login');
      }
    } else {
      Fluttertoast.showToast(msg: 'Please fill in both email and password');
    }
  }

  @override
  void dispose() {
    // clean up controllers when widget is disposed
    emailController.dispose();
    passwordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: false,
      backgroundColor: Colors.white,
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.white,
        leading: IconButton(
          onPressed: () {
            Navigator.pushReplacement(context, MaterialPageRoute(builder:
                (context) => WelcomePage()));
          },
          icon: const Icon(
            Icons.arrow_back,
            size: 20,
            color: Colors.black,
          ),
        ),
        systemOverlayStyle: SystemUiOverlayStyle.dark,
      ),
      body: SizedBox(
        height: MediaQuery.of(context).size.height,
        width: double.infinity,
        child: Form(
          key: formKey,
          child: Column(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: <Widget>[
              Expanded(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                  children: <Widget>[
                    Column(
                      children: <Widget>[
                        const Text(
                          "Login",
                          style: TextStyle(fontSize: 30, fontWeight:
                          FontWeight.bold),
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                        Text(
                          "Login to your account",
                          style: TextStyle(fontSize: 15, color: Colors
                              .grey[700]),
                        )
                      ],
                    ),
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 40),
                      child: Column(
                        children: <Widget>[
                          inputFile(label: "Email", controller:
                          emailController),
                          inputFile(label: "Password", obscureText: true,
                              controller: passwordController),
                        ],
                      ),
                    ),
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 40),
                      child: Container(
                        padding: EdgeInsets.zero,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(50),
                          border: const Border(
                            bottom: BorderSide(color: Colors.black),
                            top: BorderSide(color: Colors.black),
                            left: BorderSide(color: Colors.black),
                            right: BorderSide(color: Colors.black),
                          ),
                        ),
                        child: MaterialButton(
                          minWidth: double.infinity,
                          height: 60,
                          onPressed: () {},
                          color: const Color(0xFF4CAF50),
                          elevation: 0,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(50),
                          ),
                          child: ElevatedButton(
                            style: ButtonStyle(
                              backgroundColor: MaterialStateProperty.all<Color>
                                (Colors.green),
                            ),
                            onPressed: () async {
                              if (formKey.currentState!.validate()) {
                                _loginUser();
                              }
                            },
                            child: const Text(
                              "Login",
                              style: TextStyle(
                                fontWeight: FontWeight.w600,
                                fontSize: 18,
                                color: Colors.white,
                              ),
                            ),
                          ),
                        ),
                      ),
                    ),
                    Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: <Widget>[
                        const Text("Don't have an account?"),
                        ElevatedButton(
                          style: ButtonStyle(
                            backgroundColor: MaterialStateProperty.all<Color>
                              (Colors.green),
                          ),
                          onPressed: (){
                            Navigator.push(context, MaterialPageRoute(builder:
                                (context) => const SignupPage()));
                          },
                          child: const Text(
                            "Sign up",
                            style: TextStyle(
                              fontWeight: FontWeight.w600,
                              fontSize: 18,
                            ),
                          ),
                        )
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        )
      ),
    );
  }

  // We will create a widget for text field
  Widget inputFile({label, obscureText = false, required TextEditingController
  controller}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: <Widget>[
        Text(
          label,
          style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w400,
              color: Colors.black87),
        ),
        const SizedBox(
          height: 5,
        ),
        TextField(
          obscureText: obscureText,
          controller: controller,
          decoration: const InputDecoration(
            contentPadding: EdgeInsets.symmetric(vertical: 0, horizontal: 10),
            enabledBorder: OutlineInputBorder(
              borderSide: BorderSide(color: Colors.grey),
            ),
            border: OutlineInputBorder(borderSide: BorderSide(color: Colors
                .grey)),
          ),
        ),
        const SizedBox(height: 10),
      ],
    );
  }
}
