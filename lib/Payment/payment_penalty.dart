import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import '../Controller/connection.dart';
import '../Model/receipt_model.dart';
import '../Welcome/select_cafe.dart';

class PaymentPenaltyScreen extends StatefulWidget {

  const PaymentPenaltyScreen({
    Key? key,}) : super(key: key);

  @override
  _PaymentScreenState createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentPenaltyScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Payment Screen'),
      ),
      body: Center(
        child: ElevatedButton(
          onPressed: () async {
            await postOrderBeverage();
            // Add navigation or further actions here
          },
          child: Text('Pay'),
        ),
      ),
    );
  }

  Future<void> postOrderBeverage() async {
    try {
      final String? token = await getToken();
      SharedPreferences prefs = await SharedPreferences.getInstance();

      int bookingId = prefs.getInt('bookingId') ?? 0;

      var headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      };

      var requestBody = {
        'type': 'penalty',
        'order_id': bookingId,
      };

      var response = await http.post(
        Uri.parse(API.payment),
        headers: headers,
        body: json.encode(requestBody),
      );

      if (response.statusCode == 200) {
        print('Payment successful for item with order ID: $bookingId');
      } else {
        print('Error: ${response.statusCode}, Reason Phrase: ${response.reasonPhrase}');
      }
    } catch (error) {
      print('Error: $error');
    }
  }
}
