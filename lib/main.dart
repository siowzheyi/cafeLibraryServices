import 'package:flutter/material.dart';
import 'package:cafe_library_services/Welcome/login.dart';
import 'package:cafe_library_services/Welcome/registration.dart';

void main() {
  runApp(MaterialApp(
    debugShowCheckedModeBanner: false,
    home: HomePage(),
  )); //MaterialApp
}

class HomePage extends StatelessWidget{
  @override
  Widget build(BuildContext context){
    return Scaffold(
      body: SafeArea(
        child: Container(
          //we will give media query height
          //double.infinity make it big as parent allows
          //while MediaQuery make it big as per the screen

          width: double.infinity,
          height: MediaQuery.of(context).size.height,
          padding: EdgeInsets.symmetric(horizontal: 30, vertical: 50),
          child: Column(
            //even space distribution
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: <Widget> [
          Column(
          children: <Widget> [
            Text(
            "Welcome",
            style: TextStyle(
              fontWeight: FontWeight.bold,
              fontSize: 30,
            ), //TextStyle


          ), //Text
          SizedBox(
            height: 20,
          ), //SizedBox
          Text("This is Login & SignUp page. Please SignUp first if you are new user.",
            textAlign: TextAlign.center,
            style: TextStyle(
              color: Colors.grey[700],
              fontSize: 15,

            ),) //TextStyle, Text

          ], //<Widget>[]
        ), //Column
        Container(
          height: MediaQuery.of(context).size.height / 3,
          decoration: BoxDecoration(
              image: DecorationImage(
                  image: AssetImage("assets/welcome.png")
              )
          ),
        ),

        Column(
          children: <Widget> [
            //the login button
            MaterialButton(
              minWidth: double.infinity,
              height: 60,
              onPressed: (){
                Navigator.push(context, MaterialPageRoute(builder: (context) => LoginPage()));
              },
              //defining shape
              shape: RoundedRectangleBorder(
                  side: BorderSide(
                      color: Colors.black
                  ),
                  borderRadius: BorderRadius.circular(50)
              ),
              child: Text(
                "Login",
                style: TextStyle(
                    fontWeight: FontWeight.w600,
                    fontSize: 18
                ),
              ),

            ),
            //creating signup button
            SizedBox(height: 20),
            MaterialButton(
              minWidth: double.infinity,
              height: 60,
              onPressed: () {
                Navigator.push(context,
                    MaterialPageRoute(builder: (context) => SignupPage()));
              },
              color: Color(0xff0095FF),
              shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(50)
              ),
              child: Text(
                "Sign Up",
                style: TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.w600,
                    fontSize: 18
                ),
              ),
            )
          ],
        ),
      ]),
    ),
    ),);
  }
}
