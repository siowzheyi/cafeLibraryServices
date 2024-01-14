import 'package:cafe_library_services/Welcome/home.dart';
import 'package:cafe_library_services/Welcome/login.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Controller/connection.dart';
import '../Model/order_model.dart';
import '../Welcome/select_library.dart';

void main() {
  runApp(OrderRecordListing());
}

class OrderRecordListing extends StatelessWidget {
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
              return OrderRecordListScreen(cafeId: cafeId);
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

class OrderRecordListScreen extends StatefulWidget {
  final String cafeId;
  final Map<String, String>? headers;

  const OrderRecordListScreen(
      {Key? key, required this.cafeId, this.headers})
      : super(key: key);

  @override
  _OrderRecordListScreenState createState() => _OrderRecordListScreenState();
}

class _OrderRecordListScreenState extends State<OrderRecordListScreen> {
  late Future<List<OrderModel>> orderRecords;

  Future<List<OrderModel>> getOrderRecordList() async {
    try {
      final String userId = await getUserIdFromSharedPreferences();
      final String cafeId = await getLibraryIdFromSharedPreferences();
      final String? token = await getToken();

      // Assuming your API requires both user ID and cafe ID for fetching orders
      var url = Uri.parse('${API.orderRecord}?user_id=$userId');
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${token}"
      };

      var response = await http.get(url, headers: header);

      if (response.statusCode == 200) {
        try {
          Map<String, dynamic> result = jsonDecode(response.body);
          List<dynamic> aaDataList = result['data']['aaData'];
          List<OrderModel> orderRecords = aaDataList
              .map((data) => OrderModel.fromJson(data))
              .toList();
          return orderRecords;
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
      print('Error fetching announcements: $error');
      return [];
    }
  }

  late Future<List<OrderModel>> orderRecordList;

  @override
  void initState() {
    super.initState();
    orderRecordList = getOrderRecordList();
    fetchData();
  }

  Future<void> fetchData() async {
    if (mounted) {
      await getOrderRecordList();
      if (mounted) {
        setState(() {
          // Trigger a rebuild after data is fetched
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          title: Text('OrderRecord Listing'),
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
        body: FutureBuilder<List<OrderModel>>(
          future: orderRecordList,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return Center(child: CircularProgressIndicator());
            } else if (snapshot.hasError) {
              return Center(
                child: Text('Error: ${snapshot.error}'),
              );
            } else {
              List<OrderModel> results = snapshot.data!;
              return Container(
                child: ListView.builder(
                  itemCount: results.length,
                  itemBuilder: (context, index) {
                    return Card(
                      child: SizedBox(
                        height: 100.0,
                        child: ListTile(
                          onTap: () {
                            // Navigator.push(
                            //   context,
                            //   MaterialPageRoute(
                            //     builder: (context) => OrderRecordDetailsScreen(
                            //       name: results[index].name,
                            //       picture: results[index].picture,
                            //     ),
                            //   ),
                            // );
                          },
                          title: Row(
                            children: [
                              Container(
                                height: 60,
                                width: 60,
                                decoration: BoxDecoration(
                                  color: Colors.green,
                                  borderRadius: BorderRadius.circular(10),
                                ),
                                child: Center(
                                  child: Text(
                                    '${results[index].orderNo}',
                                    style: TextStyle(
                                      fontSize: 20,
                                      fontWeight: FontWeight.bold,
                                      color: Colors.white,
                                    ),
                                  ),
                                ),
                              ),
                              SizedBox(width: 32),
                              Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    '${results[index].beverageName}',
                                    style: TextStyle(
                                        fontWeight: FontWeight.bold),
                                  ),
                                  Text(
                                    '${results[index].totalPrice}',
                                    style: TextStyle(
                                        fontWeight: FontWeight.bold),
                                  ),
                                ],
                              ),
                            ],
                          ),
                        ),
                      ),
                    );
                  },
                ),
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
