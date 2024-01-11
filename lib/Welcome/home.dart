import 'package:cafe_library_services/Beverage/beverage_listing.dart';
import 'package:cafe_library_services/Record/booking_record.dart';
import 'package:cafe_library_services/Record/order_record.dart';
import 'package:cafe_library_services/Room/room_listing.dart';
import 'package:flutter/material.dart';
import 'package:cafe_library_services/Book/book_listing.dart';
import 'package:cafe_library_services/Equipment/equipment_listing.dart';
import 'package:cafe_library_services/Welcome/login.dart';
import '../Controller/connection.dart';
import '../Record/penalty_record.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

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
      home: HomePage(),
    );
  }
}

class HomePage extends StatefulWidget {
  const HomePage({Key? key}) : super(key: key);

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
              ViewAnnouncement(),
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
                              builder: (context) => const EquipmentListing(),
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
                              builder: (context) => const RoomListing(),
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
                              builder: (context) => const BookListing(),
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
                              builder: (context) => const BookingRecordPage(),
                            ),
                          );
                        },
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.paste),
                            Text(
                              'Browse Booking Record',
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
                              builder: (context) => PenaltyPage(),
                            ),
                          );
                        },
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.report),
                            Text(
                              'Browse Penalty Record',
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
                              builder: (context) => const BeverageListing(),
                            ),
                          );
                        },
                        style: ButtonStyle(
                          backgroundColor: MaterialStateProperty.all<Color>(Colors.brown),
                        ),
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.coffee), // Replace 'yourLeadingIcon' with the desired leading icon
                            Text(
                              'Browse Cafe Menu',
                              style: TextStyle(fontSize: 32.0),
                            ),
                            Icon(Icons.coffee), // Replace 'yourTrailingIcon' with the desired trailing icon
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
                              builder: (context) => const OrderRecordPage(),
                            ),
                          );
                        },
                        style: ButtonStyle(
                          backgroundColor: MaterialStateProperty.all<Color>(Colors.brown),
                        ),
                        child: const Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Icon(Icons.receipt_long),
                            Text(
                              'Browse Order Beverage Record',
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
            ListTile(
              title: const Text('Settings'),
              onTap: () {
                // go to profile settings
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
  List<dynamic> announcement = [];

  Future<void> getAnnouncements() async {
    try {
      var response = await http.get(Uri.parse(API.announcement));
      if (response.statusCode == 200) {
        setState(() {
          announcement = jsonDecode(response.body);
        });
        print(announcement);
      }
    } catch (ex) {
      print("Error :: " + ex.toString());
    }
  }

  @override
  void initState() {
    getAnnouncements();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        const Text('Announcements'),
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.start,
            children: List.generate(
              announcement.length,
                  (index) => Card(
                elevation: 4.0,
                child: SizedBox(
                  width: 200.0,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(announcement[index]["title"] ?? "", style: const TextStyle(
                          fontWeight: FontWeight.bold
                      ),),
                      Text(announcement[index]["content"] ?? ""),
                      SizedBox(
                        height: 250.0,
                        width: 200.0,
                        child: Image.network(
                          announcement[index]["picture"] ?? "",
                          fit: BoxFit.contain,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }
}