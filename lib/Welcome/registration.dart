import 'dart:convert';
import 'package:cafe_library_services/Controller/connection.dart';
import 'package:cafe_library_services/main.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:cafe_library_services/Welcome/login.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:http/http.dart' as http;

void main() {
  runApp(MaterialApp(
    theme: ThemeData(
      primarySwatch: Colors.green,
    ),
    debugShowCheckedModeBanner: false,
    home: SignupPage(),
  ));
}

class SignupPage extends StatefulWidget {
  const SignupPage({super.key});

  @override
  State<SignupPage> createState() => _SignupPageState();
}

class _SignupPageState extends State<SignupPage> {

  var formKey = GlobalKey<FormState>();
  TextEditingController nameController = TextEditingController();
  TextEditingController phoneNumController = TextEditingController();
  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();
  TextEditingController confirmPasswordController = TextEditingController();

  Future<void> _registerUser(String email, String password, String
  confirmPassword, String name, String phoneNo) async {

    var headers = {
      'Content-Type': 'application/json',
    };

    var request = http.Request(
      'POST',
      Uri.parse(API.register),
    );

    request.body = json.encode({
      "email": email,
      "password": password,
      "confirm_password": confirmPassword,
      "name": name,
      "phone_no": phoneNo,
    });

    request.headers.addAll(headers);

    try {
      http.StreamedResponse response = await request.send();

      if (response.statusCode == 200) {
        print(await response.stream.bytesToString());
        Fluttertoast.showToast(
            msg: "Registered successfully."
        );

        Navigator.push(context, MaterialPageRoute(
          builder: (context) => LoginPage(),
        ));

        setState(() {
          emailController.clear();
          passwordController.clear();
          confirmPasswordController.clear();
          nameController.clear();
          phoneNumController.clear();
        });
      } else {
        print('Error: ${response.reasonPhrase}');
        Fluttertoast.showToast(
            msg: "Email might be taken. \nPlease try again."
        );
      }
    } catch (ex) {
      print(ex.toString());
      Fluttertoast.showToast(
          msg: ex.toString()
      );
    }
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
            Navigator.pushReplacement(context,
                MaterialPageRoute(builder: (context) => WelcomePage()));
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
        height: MediaQuery
            .of(context)
            .size
            .height,
        width: double.infinity,
        child: Form(
          key: formKey, // Assigning the GlobalKey<FormState> to the form
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
                          "Sign Up",
                          style: TextStyle(fontSize: 30, fontWeight: FontWeight
                              .bold),
                        ),
                        const SizedBox(
                          height: 20,
                        ),
                        Text(
                          "Create an account",
                          style: TextStyle(fontSize: 15, color: Colors
                              .grey[700]),
                        )
                      ],
                    ),
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 40),
                      child: Column(
                        children: <Widget>[
                          TextField(
                            decoration: const InputDecoration(
                                labelText: 'Name'),
                            controller: nameController,
                          ),
                          TextField(
                            decoration: const InputDecoration(
                                labelText: 'Phone Number'),
                            controller: phoneNumController,
                          ),
                          TextField(
                            decoration: const InputDecoration(
                                labelText: 'Email'),
                            controller: emailController,
                          ),
                          TextField(
                            decoration: const InputDecoration(
                                labelText: 'Password'),
                            obscureText: true,
                            controller: passwordController,
                          ),
                          TextField(
                            decoration: const InputDecoration(
                                labelText: 'Confirm Password'),
                            obscureText: true,
                            controller: confirmPasswordController,
                          ),
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
                          onPressed: () async {
                            if (formKey.currentState!.validate()) {
                              await _registerUser(
                                emailController.text.trim(),
                                passwordController.text.trim(),
                                confirmPasswordController.text.trim(),
                                nameController.text.trim(),
                                phoneNumController.text.trim(),
                              );

                              Navigator.push(context, MaterialPageRoute(
                                builder: (context) => LoginPage(),
                              ));
                            }
                          },
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
                                await _registerUser(
                                  emailController.text.trim(),
                                  passwordController.text.trim(),
                                  confirmPasswordController.text.trim(),
                                  nameController.text.trim(),
                                  phoneNumController.text.trim(),
                                );
                              }
                            },
                            child: const Text(
                              "Sign up",
                              style: TextStyle(
                                backgroundColor: Colors.green,
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
                        const Text("Already have an account?"),
                        ElevatedButton(
                          style: ButtonStyle(
                            backgroundColor: MaterialStateProperty.all<Color>(
                                Colors.green),
                          ),
                          onPressed: () {
                            Navigator.push(context, MaterialPageRoute(
                                builder: (context) => LoginPage()));
                          },
                          child: const Text(
                            "Login",
                            style: TextStyle(
                              backgroundColor: Colors.green,
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
        ),
      ),
    );
  }
}