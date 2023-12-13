import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';

void main() {
  runApp(AnnouncementListing());
}

class AnnouncementListing extends StatelessWidget {
  const AnnouncementListing({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Announcement Listing',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: AnnouncementListScreen(),
    );
  }
}

class AnnouncementListScreen extends StatelessWidget {
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
        title: Text('Announcement Listing'),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => HomePage()));
          },
        ),
      ),
      body: SingleChildScrollView(
        scrollDirection: Axis.horizontal,
        child: Row(
          children: [
            for (var announcement in announcements)
              AnnouncementCard(announcement: announcement),
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
