import 'package:cafe_library_services/Room/room_listing.dart';
import 'package:flutter/material.dart';

class SearchHistory extends StatelessWidget {
  final List<Room> searchHistory; // Assuming 'Room' is the correct class name for equipment

  const SearchHistory({Key? key, required this.searchHistory}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Search History',
          style: TextStyle(
            fontSize: 24.0,
            fontWeight: FontWeight.bold,
          ),
        ),
        SizedBox(height: 8.0),
        Wrap(
          spacing: 8.0,
          children: [
            for (var room in searchHistory)
              Chip(
                label: Text(room.roomNo), // Assuming 'name' is the property you want to display
                onDeleted: () {
                  // Handle deletion of search history item
                },
              ),
          ],
        ),
      ],
    );
  }
}
