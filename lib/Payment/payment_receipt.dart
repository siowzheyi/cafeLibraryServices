import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Controller/connection.dart';
import '../Model/receipt_model.dart';
import '../Welcome/home.dart';
import '../Welcome/select_cafe.dart';

class PaymentBeverageScreen extends StatefulWidget {
  final ReceiptModel selectedPayment;

  const PaymentBeverageScreen({Key? key, required this.selectedPayment})
      : super(key: key);

  @override
  _PaymentScreenState createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentBeverageScreen> {
  List<ReceiptModel> paymentReceipts = [];

  @override
  void initState() {
    super.initState();
    fetchPaymentReceipts();
  }

  Future<void> fetchPaymentReceipts({String? category}) async {
    try {
      final String cafeId = await getCafeIdFromSharedPreferences();
      final String? token = await getToken();

      var url = Uri.parse('${API.paymentListing}?cafe_id=$cafeId');
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer $token"
      };

      var response = await http.get(
        url,
        headers: header,
      );

      if (response.statusCode == 200) {
        try {
          Map<String, dynamic> result = jsonDecode(response.body);

          // Check if 'aaData' is a List
          if (result['data']['aaData'] is List) {
            List<dynamic> paymentsList = result['data']['aaData'];

            // Create a List<PaymentModel> by mapping the payment data
            List<ReceiptModel> payments = paymentsList.map((paymentData) {
              return ReceiptModel.fromJson(paymentData);
            }).toList();

            // Now, 'payments' contains the list of PaymentModel instances
            setState(() {
              paymentReceipts = payments;
            });
          } else {
            print('Error: "aaData" is not a List');
          }
        } catch (error) {
          print('Error decoding JSON: $error');
        }
      } else {
        print('Request URL: $url');
        print('Request Headers: $header');
        print(response.statusCode);
        print(response.body);
      }
    } catch (error) {
      print('Error fetching payments: $error');
    }
  }

  @override
  Widget build(BuildContext context) {
    var selectedPayment = widget.selectedPayment;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Payment Screen'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(
                builder: (context) => const HomePage(libraryId: ''),
              ),
            );
          },
        ),
      ),
      body: ListView.builder(
        itemCount: paymentReceipts.length,
        itemBuilder: (context, index) {
          var receipt = paymentReceipts[index];
          return ListTile(
            title: Text('Receipt ${receipt.id}'),
            subtitle: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  receipt.cafeName,
                  style: const TextStyle(
                    fontWeight: FontWeight.bold,
                    fontSize: 32.0,
                  ),
                ),
                const SizedBox(height: 16.0),
                Text('Receipt number: ${receipt.receiptNo}'),
                Text('Name: ${receipt.userName}'),
                Text('Order number: ${receipt.orderNo}'),
                Text('Created at: ${receipt.createdAt}'),
                Text('Status: ${receipt.status}'),
                const SizedBox(height: 16.0),
                Text('Order item: ${receipt.itemName}'),
                Text('Unit price: RM${receipt.unitPrice}'),
                Text('Quantity: ${receipt.quantity}'),
                const SizedBox(height: 16.0),
                Text('Subtotal: RM${receipt.subtotal}'),
                Text('SST amount: RM${receipt.sstAmount}'),
                Text('Service charge: RM${receipt.serviceCharge}'),
                const SizedBox(height: 16.0),
                Text('Total price: RM${receipt.totalPrice}'),
              ],
            ),
            onTap: () {
              // Handle tap on the selected payment, if needed
            },
          );
        },
      ),
    );
  }
}
