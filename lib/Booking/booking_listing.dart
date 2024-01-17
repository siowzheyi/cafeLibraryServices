import 'package:cafe_library_services/Controller/connection.dart';
import 'package:cafe_library_services/Penalty/penalty_listing.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import '../Model/booking_model.dart';
import 'booking_record.dart';
import '../Welcome/select_cafe.dart';

void main() {
  runApp(BookingListing());
}

class BookingListing extends StatelessWidget {
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
              String libraryId = snapshot.data ?? '';
              return BookingListScreen(libraryId: libraryId);
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

class BookingListScreen extends StatefulWidget {
  final String libraryId;
  final Map<String, String>? headers;

  const BookingListScreen({Key? key, required this.libraryId, this.headers}) : super(key: key);

  @override
  _BookingListScreenState createState() => _BookingListScreenState();
}

class FetchBooking {

  Future<List<BookingModel>> fetchBookingRecords() async {
    final String libraryId = await getLibraryIdFromSharedPreferences();
    final String? token = await getToken();

    try {
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer $token"
      };
      var response = await http.get(Uri.parse('${API.booking}?library_id=$libraryId'),
          headers: header);

      if (response.statusCode == 200) {
        try {
          Map<String, dynamic> result = jsonDecode(response.body);

          if (result['status'] == 'success' &&
              result['data'] is Map &&
              result['data']['aaData'] is List) {
            List<dynamic> aaDataList = result['data']['aaData'];
            List<BookingModel> bookingRecords = [];

            for (var bookingData in aaDataList) {
              BookingModel booking = BookingModel.fromJson(bookingData);

              bookingRecords.add(booking);
            }

            return bookingRecords;
          } else {
            print('Error: "status" is not "success" or "aaData" is not a List');
            print('Response body: ${response.body}');
            return [];
          }
        } catch (e) {
          print('Error decoding JSON: $e');
          print('Response body: ${response.body}');
          return [];
        }
      } else {
        print('Error statusCode: ${response.statusCode}, Reason: ${response.reasonPhrase}');
        print('Response body: ${response.body}');
        return [];
      }
    } catch (error) {
      print('Error: $error');
      return [];
    }
  }

  void saveBookingIdToSharedPreferences(int bookingId) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setInt('bookingId', bookingId);
  }

  Future<bool> toggleReturn(int bookingId) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? token = await getToken();

    try {
      var response = await http.patch(
        Uri.parse('${API.returnBooking}/$bookingId'),
        headers: {
          "Content-Type": "application/json",
          "Authorization": "Bearer $token"
        },
        body: jsonEncode({"type": "return"}),
      );

      if (response.statusCode == 200) {
        print('Return toggled successfully for bookingId: $bookingId');
        Fluttertoast.showToast(msg: 'Return successfully');
        return true;
      } else {
        print('Error toggling return - StatusCode: ${response.statusCode}');
        return false;
      }
    } catch (error) {
      print('Error toggling return: $error');
      return false;
    }
  }
}

class _BookingListScreenState extends State<BookingListScreen> {
  late Future<List<BookingModel>> bookingList;
  late List<BookingModel> results;
  Map<int, bool> isPaymentRequiredMap = {};

  @override
  void initState() {
    super.initState();
    fetchData();
  }

  Future<void> fetchData() async {
    bookingList = FetchBooking().fetchBookingRecords();
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          title: Text('Booking Records'),
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
              child: FutureBuilder<List<BookingModel>>(
                future: bookingList,
                builder: (context, snapshot) {
                  if (snapshot.connectionState == ConnectionState.waiting) {
                    return Center(child: CircularProgressIndicator());
                  } else if (snapshot.hasError) {
                    return Center(
                      child: Text('Error: ${snapshot.error}'),
                    );
                  } else {
                    results = snapshot.data!;
                    return GestureDetector(
                      child: ListView.builder(
                        itemCount: results.length,
                        itemBuilder: (context, index) {
                          var booking = results[index];
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
                                          'Item: ${booking.itemName}',
                                          style: TextStyle(
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),
                                        Text(
                                          'Status: ${booking.status}',
                                        ),
                                      ],
                                    ),
                                    Spacer(),
                                    Padding(
                                      padding: const EdgeInsets.all(16.0),
                                      child: Visibility(
                                        visible: booking.status == 'approved',
                                        child: ElevatedButton(
                                          onPressed: () async {
                                            if (booking.end != true) {
                                              bool success = await FetchBooking().toggleReturn(booking.bookingId);
                                              if (success && booking.penaltyStatus == 1) {
                                                setState(() {
                                                  isPaymentRequiredMap[booking.bookingId] = true;
                                                });

                                                // Wait for user to click "Pay" button before navigating to PenaltyListing
                                                await showDialog(
                                                  context: context,
                                                  builder: (BuildContext context) {
                                                    return AlertDialog(
                                                      title: Text('Payment Confirmation'),
                                                      content: Text('Do you want to proceed with the payment?'),
                                                      actions: <Widget>[
                                                        TextButton(
                                                          onPressed: () {
                                                            Navigator.of(context).pop();
                                                          },
                                                          child: Text('Cancel'),
                                                        ),
                                                        TextButton(
                                                          onPressed: () {
                                                            Navigator.of(context).pop();
                                                            Navigator.push(
                                                              context,
                                                              MaterialPageRoute(
                                                                builder: (context) => PenaltyListing(),
                                                              ),
                                                            );
                                                          },
                                                          child: Text('Pay'),
                                                        ),
                                                      ],
                                                    );
                                                  },
                                                );
                                              }
                                            }
                                          },
                                          child: IgnorePointer(
                                            ignoring: isPaymentRequiredMap[booking.bookingId] == true,
                                            child: Text(
                                              isPaymentRequiredMap[booking.bookingId] == true ? 'Pay' : (booking.end == true ? 'Penalty' : 'Return'),
                                            ),
                                          ),
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                                onTap: () {
                                  // Handle the tap for individual ListTiles
                                  var selectedBooking = results[index];
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (context) => BookingDetailPage(booking: selectedBooking),
                                    ),
                                  );
                                },
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