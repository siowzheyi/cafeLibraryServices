import 'package:cafe_library_services/Controller/connection.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
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
  late List<BookingModel> beverages;

  Future<List<BookingModel>> fetchBookingRecords() async {
    // Replace the URL with your actual API endpoint
    final String libraryId = await getLibraryIdFromSharedPreferences();
    final String? token = await getToken();

    try {
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${token}"
      };
      var response = await http.get(Uri.parse('${API.booking}?library_id=$libraryId'),
      headers: header);

      if (response.statusCode == 200) {
        Map<String, dynamic> result = jsonDecode(response.body);

        // Check if 'data' is a Map and contains 'aaData' key
        if (result['status'] == 'success' &&
            result['data'] is Map &&
            result['data']['aaData'] is List) {
          List<dynamic> aaDataList = result['data']['aaData'];
          List<BookingModel> bookingRecords = [];

          // Iterate through the 'aaData' list
          for (var bookingData in aaDataList) {
            // Create a BookingModel instance from each booking data and add it to the list
            bookingRecords.add(BookingModel.fromJson(bookingData));
          }

          return bookingRecords;
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

class _BookingListScreenState extends State<BookingListScreen> {
  late Future<List<BookingModel>> bookingList;
  late List<BookingModel> results;

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
                                          '${booking.itemName}',
                                          style: TextStyle(
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),
                                        Text(
                                          '${booking.status}',
                                        ),
                                      ],
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