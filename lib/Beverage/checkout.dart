import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../Controller/connection.dart';
import '../Model/beverage_model.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Payment/payment_receipt.dart';
import '../Welcome/select_cafe.dart';

class CheckoutScreen extends StatelessWidget {
  final List<BeverageModel> selectedItems;
  final List<int> quantity;
  final double totalPrice;

  const CheckoutScreen({
    Key? key,
    required this.selectedItems,
    required this.quantity,
    required this.totalPrice,
  }) : super(key: key);

  Future<void> postOrderBeverage(List<BeverageModel> selectedItems, List<int>
  quantity, int tableId) async {
    List<int> orderIds = [];
    try {
      final String? token = await getToken();

      for (int i = 0; i < selectedItems.length; i++) {
        var headers = {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        };

        var requestBody = {
          'beverage_id': selectedItems[i].id,
          'quantity': quantity[i],
          'table_id': tableId,
        };

        var response = await http.post(
          Uri.parse(API.order),
          headers: headers,
          body: json.encode(requestBody),
        );

        if (response.statusCode == 200) {
          var orderId = json.decode(response.body)['order_id'];
          orderIds.add(orderId);
          print('Order placed successfully for beverage ${selectedItems[i]
              .id}');
        } else {
          print('Error: ${response.statusCode}, Reason Phrase: ${response
              .reasonPhrase}');
        }
      }
    } catch (error) {
      print('Error: $error');
    }
  }

  Future<void> payBeverage() async {
    try {
      final String? token = await getToken();
      SharedPreferences prefs = await SharedPreferences.getInstance();

      int orderId = prefs.getInt('orderId') ?? 0;

      var headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      };

      var requestBody = {
        'type': 'order',
        'order_id': orderId,
      };

      var response = await http.post(
        Uri.parse(API.payment),
        headers: headers,
        body: json.encode(requestBody),
      );

      if (response.statusCode == 200) {
        print('Payment successful for item with order ID: $orderId');
      } else {
        print('Error: ${response.statusCode}, Reason Phrase: ${response
            .reasonPhrase}');
      }
    } catch (error) {
      print('Error: $error');
    }
  }


  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Checkout'),
      ),
      body: ListView.builder(
        itemCount: selectedItems.length,
        itemBuilder: (context, index) {
          double itemTotal = double.parse(selectedItems[index].price)
              * quantity[index];
          return SingleChildScrollView(
            child: SizedBox(
              height: 80.0,
              child: Card(
                child: ListTile(
                  title: Text(selectedItems[index].name),
                  subtitle: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text('Quantity: ${quantity[index]}'),
                      Text('Total Price: RM${itemTotal.toStringAsFixed(2)}'),
                    ],
                  ),
                ),
              ),
            ),
          );
        },
      ),
      bottomNavigationBar: BottomAppBar(
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // Display total price for all items
              Text('Grand Total Price: RM${totalPrice.toStringAsFixed(2)}'),
              const SizedBox(height: 50.0),
              ElevatedButton(
                onPressed: () {
                  _handlePayment(context);
                },
                child: const Text('Pay'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  void _handlePayment(BuildContext context) async {
    // Show a loading screen or perform payment logic here
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return const AlertDialog(
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              CircularProgressIndicator(),
              SizedBox(height: 16.0),
              Text('Processing payment...'),
            ],
          ),
        );
      },
    );

    Future.delayed(const Duration(seconds: 2), () async {
      double selectedPayment = 0.0;
      // Close the loading screen
      Navigator.of(context).pop();
      SharedPreferences prefs = await SharedPreferences.getInstance();
      int? tableId = prefs.getInt('tableId');

      // Check if tableId is not null before calling postOrderBeverage
      if (tableId != null) {
        await postOrderBeverage(selectedItems, quantity, tableId);
        await payBeverage();
        // Assign total price to selectedPayment
        selectedPayment = totalPrice;

        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              content: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(Icons.check_circle, color: Colors.green, size: 40.0),
                  SizedBox(height: 16.0),
                  Text('Order successfully sent!'),
                ],
              ),
              actions: [
                TextButton(
                  onPressed: () async {
                    Navigator.of(context).pop();
                    // Navigator.push(
                    //   context,
                    //   MaterialPageRoute(
                    //     builder: (context) => PaymentBeverageScreen(),
                    //   ),
                    // );
                  },
                  child: const Text('OK'),
                ),
              ],
            );
          },
        );
      } else {
        print('Error: tableId is null');
      }
    });
  }
}
