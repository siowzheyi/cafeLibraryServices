import 'package:cafe_library_services/Payment/payment_receipt.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Controller/connection.dart';
import '../Model/receipt_model.dart';
import '../Welcome/select_library.dart';

void main() {
  runApp(PaymentBeverageListing());
}

class PaymentBeverageListing extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: FutureBuilder<String>(
        future: getLibraryIdFromSharedPreferences(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.done) {
            if (snapshot.hasError) {
              // Handle error
              return Scaffold(
                body: Center(
                  child: Text('Error: ${snapshot.error}'),
                ),
              );
            } else {
              String cafeId = snapshot.data ?? '';
              return PaymentBeverageListScreen(cafeId: cafeId);
            }
          } else {
            return const Scaffold(
              body: Center(
                child: CircularProgressIndicator(),
              ),
            );
          }
        },
      ),
    );
  }
}

class PaymentBeverageListScreen extends StatefulWidget {
  final String cafeId;
  final Map<String, String>? headers;

  const PaymentBeverageListScreen({Key? key, required this.cafeId, this
      .headers}) : super(key: key);

  @override
  _PaymentBeverageListScreenState createState() =>
      _PaymentBeverageListScreenState();
}

class FetchPayment {
  late List<ReceiptModel> payments;

  Future<List<ReceiptModel>> getPaymentList({String? category}) async {
    try {
      final String cafeId = await getCafeIdFromSharedPreferences();
      final String? token = await getToken();
      payments = [];

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

            return payments;
          } else {
            print('Error: "aaData" is not a List');
            return [];
          }
        } catch (error) {
          print('Error decoding JSON: $error');
          return [];
        }
      }

      print('Request URL: $url');
      print('Request Headers: $header');
      print(response.statusCode);
      print(response.body);
      return [];
    } catch (error) {
      print('Error fetching payments: $error');
      return [];
    }
  }
}

class _PaymentBeverageListScreenState extends State<PaymentBeverageListScreen> {
  late Future<List<ReceiptModel>> paymentList;
  late List<ReceiptModel> payments;

  @override
  void initState() {
    super.initState();
    fetchData();
  }

  Future<void> fetchData() async {
    paymentList = FetchPayment().getPaymentList();
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          title: const Text('Payment Listing'),
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
        body: FutureBuilder<List<ReceiptModel>>(
          future: paymentList,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            } else if (snapshot.hasError) {
              return Center(
                child: Text('Error: ${snapshot.error}'),
              );
            } else {
              payments = snapshot.data!;

              if (payments.isEmpty) {
                return const Center(child: Text('No payment data available.'));
              }

              return ListView.builder(
                itemCount: payments.length,
                itemBuilder: (context, index) {
                  var payment = payments[index];
                  return Card(
                    child: ListTile(
                      title: Text('Order No: ${payment.receiptNo}'),
                      subtitle: Row(
                        children: [
                          Column(
                            children: [
                              Text('Order: ${payment.itemName}'),
                              Text('Total price: RM${payment.totalPrice}'),
                            ],
                          ),
                        ],
                      ),
                      onTap: () {
                        var payment = payments[index];
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => PaymentBeverageScreen(selectedPayment: payment),
                          ),
                        );
                      },
                    ),
                  );
                },
              );
            }
          },
        ),
      ),
    );
  }

  @override
  void dispose() {
    // Clean up resources, cancel timers, etc.
    super.dispose();
  }
}
