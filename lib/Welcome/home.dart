import 'package:cafe_library_services/Beverage/beverage_listing.dart';
import 'package:cafe_library_services/Record/booking_record.dart';
import 'package:cafe_library_services/Record/order_record.dart';
import 'package:cafe_library_services/Room/room_listing.dart';
import 'package:flutter/material.dart';
import 'package:cafe_library_services/Book/book_listing.dart';
import 'package:cafe_library_services/Equipment/equipment_listing.dart';
import 'package:cafe_library_services/Welcome/login.dart';
import '../Record/penalty_record.dart';

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

class HomePage extends StatelessWidget {

  final List<Announcement> announcements = [
    Announcement('24H Opening!', 'We are open for 24 hours starting from today', 'assets/24h.jpg'),
    Announcement('Anugerah Dekan', 'Anugerah Dekan bagi sesi 2022/2023', 'assets/anugerah_dekan.jpg'),
    Announcement('Library construction', 'Construction will be made from 14 to 15 of December', 'assets/construction.jpg'),
    // Add more announcements as needed
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Home Page'),
      ),
      body: Stack(
        children: [
          SingleChildScrollView(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.start,
                children: [
                  Text('Welcome username',
                    style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 32.0
                    ),),
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: [
                        for (var announcement in announcements)
                          AnnouncementCard(announcement: announcement),
                      ],
                    ),
                  ),
                  Container(
                    height: MediaQuery.of(context).size.height,
                    child: GridView.count(
                      crossAxisCount: 3,
                      padding: EdgeInsets.all(16.0),
                      mainAxisSpacing: 16.0,
                      crossAxisSpacing: 16.0,
                      children: [
                        ElevatedButton(
                          onPressed: () {
                            Navigator.push(context, MaterialPageRoute(builder: (context) => EquipmentListing()));
                          },
                          child: Text('Browse equipment',
                          style: TextStyle(
                            fontSize: 32.0
                          ),),
                        ),
                        ElevatedButton(
                          onPressed: () {
                            Navigator.push(context, MaterialPageRoute(builder: (context) => RoomListing()));
                          },
                          child: Text('Browse Room',
                            style: TextStyle(
                                fontSize: 32.0
                            ),),
                        ),
                        ElevatedButton(
                          onPressed: () {
                            Navigator.push(context, MaterialPageRoute(builder: (context) => BookListing()));
                          },
                          child: Text('Browse Book',
                            style: TextStyle(
                                fontSize: 32.0
                            ),),
                        ),
                        ElevatedButton(
                          onPressed: () {
                            Navigator.push(context, MaterialPageRoute(builder: (context) => BeverageListing()));
                          },
                          child: Text('Browse Cafe Menu',
                            style: TextStyle(
                                fontSize: 32.0
                            ),),
                        ),
                        ElevatedButton(
                          onPressed: () {
                            Navigator.push(context, MaterialPageRoute(builder: (context) => BookingRecordPage()));
                          },
                          child: Text('Browse Booking Record',
                            style: TextStyle(
                                fontSize: 32.0
                            ),),
                        ),
                        ElevatedButton(
                          onPressed: () {
                            Navigator.push(context, MaterialPageRoute(builder: (context) => OrderRecordPage()));
                          },
                          child: Text('Browse Order Beverage Record',
                            style: TextStyle(
                                fontSize: 32.0
                            ),),
                        ),
                        ElevatedButton(
                          onPressed: () {
                            Navigator.push(context, MaterialPageRoute(builder: (context) => PenaltyPage()));
                          },
                          child: Text('Browse Penalty Record',
                            style: TextStyle(
                                fontSize: 32.0
                            ),),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          )
        ],
      ),
      drawer: Drawer(
        child: ListView(
          padding: EdgeInsets.zero,
          children: [
            DrawerHeader(
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
              title: Text('Logout'),
              onTap: () {
                // go to login page
                Navigator.push(context, MaterialPageRoute(builder: (context) => LoginPage()));
              },
            ),
            ListTile(
              title: Text('Settings'),
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

class Announcement {
  final String title;
  final String content;
  final String imageUrl;

  Announcement(this.title, this.content, this.imageUrl);
}

class AnnouncementCard extends StatelessWidget {
  final Announcement announcement;

  const AnnouncementCard({Key? key, required this.announcement}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.all(8.0),
      height: 300.0,
      width: 400.0, // Adjust the width of each card
      child: Card(
        elevation: 4.0,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Image.asset(
              announcement.imageUrl,
              width: double.infinity,
              height: 150.0,
              fit: BoxFit.cover,
            ),
            Padding(
              padding: const EdgeInsets.all(8.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Announcement:',
                    style: TextStyle(
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  SizedBox(height: 8.0),
                  Text(
                    announcement.content,
                    style: TextStyle(fontSize: 16.0),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}