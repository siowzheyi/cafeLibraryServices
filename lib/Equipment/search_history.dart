import 'package:flutter/material.dart';
import 'package:cafe_library_services/Equipment/equipment_listing.dart';

class SearchHistory extends StatelessWidget {
  final List<Equipment> searchHistory;

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
            for (var equipment in searchHistory)
              Chip(
                label: Text(equipment.name), // Assuming name is the property you want to display
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
