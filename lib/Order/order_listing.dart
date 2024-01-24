import 'package:cafe_library_services/Controller/connection.dart';
import 'package:cafe_library_services/Order/order_record.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Model/order_model.dart';
import '../Welcome/select_cafe.dart';

void main() {
  runApp(OrderListing());
}

class OrderListing extends StatelessWidget {
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
              return OrderListScreen(cafeId: cafeId);
            }
          } else {
            // While waiting for the Future to complete, show a loading indicator
            return Scaffold(
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

class OrderListScreen extends StatefulWidget {
  final String cafeId;
  final Map<String, String>? headers;

  const OrderListScreen({Key? key, required this.cafeId, this.headers}) : super(key: key);

  @override
  _OrderListScreenState createState() => _OrderListScreenState();
}

class FetchOrder {
  late List<OrderModel> beverages;

  Future<List<OrderModel>> fetchOrderRecords() async {
    // Replace the URL with your actual API endpoint
    final String cafeId = await getCafeIdFromSharedPreferences();
    final String? token = await getToken();

    try {
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer $token"
      };
      var response = await http.get(Uri.parse('${API.ordering}'),
      headers: header);

      if (response.statusCode == 200) {
        Map<String, dynamic> result = jsonDecode(response.body);

        // Check if 'data' is a Map and contains 'aaData' key
        if (result['status'] == 'success' &&
            result['data'] is Map &&
            result['data']['aaData'] is List) {
          List<dynamic> aaDataList = result['data']['aaData'];
          List<OrderModel> orderRecords = [];

          // Iterate through the 'aaData' list
          for (var orderData in aaDataList) {
            // Create an OrderRecord instance from each order data and add it to the list
            orderRecords.add(OrderModel.fromJson(orderData));
          }

          return orderRecords;
        } else {
          print('Error: "status" is not "success" or "aaData" is not a List');
          return [];
        }
      } else {
        print('Error statusCode: ${response.statusCode}, Reason: ${response.reasonPhrase}');
        return [];
      }
    } catch (error) {
      print('Error: $error');
      return [];
    }
  }
}

class _OrderListScreenState extends State<OrderListScreen> {

  late Future<List<OrderModel>> orderList;
  late List<OrderModel> results;

  @override
  void initState() {
    fetchData();
  }

  Future<void> fetchData() async {
    orderList = FetchOrder().fetchOrderRecords();
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          title: Text('Order Records'),
          leading: IconButton(
            icon: const Icon(Icons.arrow_back),
            onPressed: () {
              Navigator.pushReplacement(
                context,
                MaterialPageRoute(
                  builder: (context) => HomePage(libraryId: ''),
                ),
              );
            },
          ),
        ),
        body: Column(
          children: [
            Expanded(
              child: FutureBuilder<List<OrderModel>>(
                future: orderList,
                builder: (context, snapshot) {
                  if (snapshot.connectionState == ConnectionState.waiting) {
                    return Center(child: CircularProgressIndicator());
                  } else if (snapshot.hasError) {
                    return Center(
                      child: Text('Error: ${snapshot.error}'),
                    );
                  } else {
                    results = snapshot.data!;
                    //results.shuffle();
                    return ListView.builder(
                      itemCount: results.length,
                      itemBuilder: (context, index) {
                        var order = results[index];
                        return Card(
                          child: SizedBox(
                            height: 100.0,
                            child: ListTile(
                              title: Row(
                                children: [
                                  SizedBox(width: 32),
                                  Column(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        '${order.orderNo}',
                                        style: TextStyle(
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                      Text(
                                        '${order.status}',
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                              onTap: () {
                                // Handle the tap for individual ListTiles
                                var selectedOrder = results[index];
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (context) =>OrderDetailPage(order: selectedOrder),
                                  ),
                                );
                              },
                            ),
                          ),
                        );
                      },
                    );
                  }
                },
              ),
            ),
          ],
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
