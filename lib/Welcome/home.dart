import 'package:cafe_library_services/Book/book_listing.dart';
import 'package:cafe_library_services/Book/rent_book.dart';
import 'package:cafe_library_services/Equipment/equipment_listing.dart';
import 'package:cafe_library_services/Report/report_damage.dart';
import 'package:flutter/material.dart';

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
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Home Page'),
      ),
      body: Center(
        child: Text('Welcome to the Cafe Library Services System!'),
      ),
      drawer: Drawer(
        child: ListView(
          padding: EdgeInsets.zero,
          children: [
            DrawerHeader(
              decoration: BoxDecoration(
                color: Colors.blue,
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
              title: Text('Browse equipment'),
              onTap: () {
                // Handle item 1 tap
                //Navigator.pop(context);
                Navigator.push(context, MaterialPageRoute(builder: (context) => EquipmentListing()));
              },
            ),
            ListTile(
              title: Text('Browse Book'),
              onTap: () {
                // Handle item 2 tap
                //Navigator.pop(context);
                Navigator.push(context, MaterialPageRoute(builder: (context) => BookListing()));
              },
            ),
            ListTile(
              title: Text('Report Damage'),
              onTap: () {
                // Handle item 3 tap
                //Navigator.pop(context);
                Navigator.push(context, MaterialPageRoute(builder: (context) => ReportDamage()));
              },
            ),
          ],
        ),
      ),
    );
  }
}
