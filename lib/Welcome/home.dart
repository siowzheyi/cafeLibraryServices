import 'package:cached_network_image/cached_network_image.dart';
import 'package:cafe_library_services/Booking/booking_listing.dart';
import 'package:cafe_library_services/Penalty/penalty_listing.dart';
import 'package:cafe_library_services/Report/report_listing.dart';
import 'package:cafe_library_services/Room/room_listing.dart';
import 'package:cafe_library_services/Welcome/select_cafe.dart' as cafe;
import 'package:flutter/material.dart';
import 'package:cafe_library_services/Book/book_listing.dart';
import 'package:cafe_library_services/Equipment/equipment_listing.dart';
import 'package:cafe_library_services/Welcome/login.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../Controller/connection.dart';
import '../Model/announcement_model.dart';
import '../Order/order_listing.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Payment/payment_beverage_listing.dart';
import 'select_library.dart';

void main() {
  runApp(CafeLibraryServicesApp());
}

class CafeLibraryServicesApp extends StatelessWidget {
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
              // Use the libraryId value to create HomePage
              String libraryId = snapshot.data ?? '';
              return HomePage(libraryId: libraryId);
            }
          } else {
            // While waiting for the Future to complete, show a loading
            // indicator
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

Future<String> getLibraryIdFromSharedPreferences() async {
  SharedPreferences prefs = await SharedPreferences.getInstance();
  return prefs.getString('libraryId') ?? ''; // Default to an empty string if
  // not found
}

Future<String> getCafeIdFromSharedPreferences() async {
  SharedPreferences prefs = await SharedPreferences.getInstance();
  return prefs.getString('cafeId') ?? ''; // Default to an empty string if
  // not found
}

Future<String> getUserIdFromSharedPreferences() async {
  SharedPreferences prefs = await SharedPreferences.getInstance();
  return prefs.getString('userId') ?? ''; // Default to an empty string if
  // not found
}

class HomePage extends StatefulWidget {
  final String libraryId;
  final Map<String, String>? headers;

  const HomePage({Key? key, required this.libraryId, this.headers})
      : super(key: key);

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Home Page'),
      ),
      body: SingleChildScrollView(
        child: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.start,
            children: [
              const Text(
                'Welcome',
                style: TextStyle(
                  fontWeight: FontWeight.bold,
                  fontSize: 32.0,
                ),
              ),
              const ViewAnnouncement(),
              SizedBox(
                height: MediaQuery.of(context).size.height,
                child: ListView(
                  padding: const EdgeInsets.all(16.0),
                  children: [
                    SizedBox(
                      height: 60.0,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => EquipmentListing(),
                            ),
                          );
                        },
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.gamepad),
                            Text(
                              'Browse Equipment',
                              style: TextStyle(fontSize: 32.0),
                            ),
                            Icon(Icons.gamepad),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(
                      height: 16.0,
                    ),
                    SizedBox(
                      height: 60.0,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => RoomListing(),
                            ),
                          );
                        },
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.room_preferences),
                            Text(
                              'Browse Room',
                              style: TextStyle(fontSize: 32.0),
                            ),
                            Icon(Icons.room_preferences),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(
                      height: 16.0,
                    ),
                    SizedBox(
                      height: 60.0,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => BookListing(),
                            ),
                          );
                        },
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.book),
                            Text(
                              'Browse Book',
                              style: TextStyle(fontSize: 32.0),
                            ),
                            Icon(Icons.book),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(
                      height: 16.0,
                    ),
                    SizedBox(
                      height: 60.0,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => BookingListing(),
                            ),
                          );
                        },
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.paste),
                            Text(
                              'Booking Record',
                              style: TextStyle(fontSize: 32.0),
                            ),
                            Icon(Icons.paste),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(
                      height: 16.0,
                    ),
                    SizedBox(
                      height: 60.0,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => PenaltyListing(),
                            ),
                          );
                        },
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.attach_money),
                            Text(
                              'Penalty Record',
                              style: TextStyle(fontSize: 32.0),
                            ),
                            Icon(Icons.attach_money),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(
                      height: 16.0,
                    ),
                    SizedBox(
                      height: 60.0,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => ReportListing(),
                            ),
                          );
                        },
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.report),
                            Text(
                              'Report Record',
                              style: TextStyle(fontSize: 32.0),
                            ),
                            Icon(Icons.report),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(
                      height: 16.0,
                    ),
                    SizedBox(
                      height: 60.0,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => cafe.CafeSelectionScreen(),
                            ),
                          );
                        },
                        style: ButtonStyle(
                          backgroundColor: MaterialStateProperty.all<Color>
                            (Colors.brown),
                        ),
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.coffee),
                            Text(
                              'Browse Cafe Menu',
                              style: TextStyle(fontSize: 32.0),
                            ),
                            Icon(Icons.coffee),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(
                      height: 16.0,
                    ),
                    SizedBox(
                      height: 60.0,
                      child: ElevatedButton(
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => PaymentBeverageListing(),
                            ),
                          );
                        },
                        style: ButtonStyle(
                          backgroundColor: MaterialStateProperty.all<Color>
                            (Colors.brown),
                        ),
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.receipt_long),
                            Text(
                              'Order Beverage Record',
                              style: TextStyle(fontSize: 32.0),
                            ),
                            Icon(Icons.receipt_long),
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
      drawer: Drawer(
        child: ListView(
          padding: EdgeInsets.zero,
          children: [
            const DrawerHeader(
              decoration: BoxDecoration(
                color: Colors.green,
              ),
              child: Text(
                'Menu',
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 24,
                ),
              ),
            ),
            ListTile(
              title: const Text('Logout'),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => const LoginPage(),
                  ),
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}

class ViewAnnouncement extends StatefulWidget {
  const ViewAnnouncement({Key? key}) : super(key: key);

  @override
  State<ViewAnnouncement> createState() => _ViewAnnouncementState();
}

class _ViewAnnouncementState extends State<ViewAnnouncement> {
  late Future<List<AnnouncementModel>> announcements;

  Future<List<AnnouncementModel>> getAnnouncementList() async {
    try {
      final String libraryId = await getLibraryIdFromSharedPreferences();
      final String? token = await getToken();

      var url = Uri.parse('${API.announcement}?library_id=$libraryId');
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer $token"
      };
      var response = await http.get(
          url,
          headers: header
      );

      if(response.statusCode == 200) {
        try {
          Map<String, dynamic> result = jsonDecode(response.body);
          List<dynamic> aaDataList = result['data']['aaData'];
          List<AnnouncementModel> announcements = aaDataList
              .map((data) => AnnouncementModel.fromJson(data))
              .toList();
          return announcements;
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

  @override
  void initState() {
    announcements = getAnnouncementList();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        const Text('Announcements'),
        FutureBuilder<List<AnnouncementModel>>(
          future: announcements,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const CircularProgressIndicator();
              // while data is being fetched
            } else if (snapshot.hasError) {
              return Text('Error: ${snapshot.error}');
            } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
              return Text('No announcements available.');
            } else {
              List<AnnouncementModel> announcementList = snapshot.data!;
              return SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.start,
                  children: List.generate(
                    announcementList.length,
                        (index) => Card(
                      elevation: 4.0,
                      child: SizedBox(
                        width: 200.0,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(announcementList[index].title ?? "",
                                style: const TextStyle(
                                    fontWeight: FontWeight.bold)),
                            Text(announcementList[index].content ?? ""),
                            SizedBox(
                              height: 250.0,
                              width: 200.0,
                              child: CachedNetworkImage(
                                imageUrl: announcementList[index]
                                    .picture,
                                fit: BoxFit.contain,
                                placeholder: (context, url) =>
                                    const CircularProgressIndicator(),
                                errorWidget: (context, url, error) =>
                                    const Icon(Icons.error),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                ),
              );
            }
          },
        ),
      ],
    );
  }
}