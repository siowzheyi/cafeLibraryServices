import 'dart:convert';
import 'package:cafe_library_services/Model/report_model.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../Controller/connection.dart';

class RoomReportListPage extends StatefulWidget {
  @override
  _ReportListPageState createState() => _ReportListPageState();
}

class _ReportListPageState extends State<RoomReportListPage> {
  List<RoomReportModel> reports = [];

  @override
  void initState() {
    super.initState();
    // Fetch the submitted reports when the widget is initialized
    fetchReports();
  }

  Future<List<RoomReportModel>> fetchReports() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String token = prefs.getString('token') ?? '';

    try {
      var headers = {
        "Content-Type": "application/json",
        'Authorization': 'Bearer $token',
      };

      var response = await http.get(Uri.parse(API.reportListing), headers: headers);

      if (response.statusCode == 200) {
        try {
          Map<String, dynamic> result = jsonDecode(response.body);

          // Check if 'data' is a Map and contains 'aaData' key
          if (result['data'] is Map && result['data']['aaData'] is List) {
            List<dynamic> aaDataList = result['data']['aaData'];
            List<RoomReportModel> reports = [];

            // Iterate through the 'aaData' list
            for (var aaData in aaDataList) {
              // Check if 'rooms' is a List
              if (aaData['rooms'] is List) {
                List<dynamic> roomsList = aaData['rooms'];

                // Iterate through the 'rooms' list
                for (var roomData in roomsList) {
                  // Create a BookReportModel instance from each room data and add it to the list
                  reports.add(RoomReportModel.fromJson(roomData));
                }
              }
            }

            return reports;
          } else {
            print('Error: "data" is not a Map or "aaData" is not a List');
            return [];
          }
        } catch (error) {
          print('Error decoding JSON: $error');
          return [];
        }
      } else {
        print('Error statusCode: ${response.reasonPhrase}');
        return [];
      }
    } catch (error) {
      print('Error: $error');
      return [];
    }
  }

  Future<void> deleteReport(int reportId) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String token = prefs.getString('token') ?? '';

    try {
      var headers = {
        "Content-Type": "application/json",
        'Authorization': 'Bearer $token',
      };

      var response = await http.delete(Uri.parse('${API.room}?report_id=$reportId'), headers: headers);

      if (response.statusCode == 200) {
        // Report deleted successfully, refresh the list
        await fetchReports();
      } else {
        // Handle the error - you might want to show an error message to the user
        print('Error deleting report. StatusCode: ${response.statusCode}, Reason: ${response.reasonPhrase}');
      }
    } catch (error) {
      // Handle other errors - you might want to show an error message to the user
      print('Error: $error');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Submitted Reports'),
      ),
      body: ListView.builder(
        itemCount: reports.length,
        itemBuilder: (context, index) {
          var report = reports[index];
          return ListTile(
            title: Text(report.name),  // Access 'name' using the getter
            subtitle: Text(report.description),  // Access 'description' using the getter
            trailing: IconButton(
              icon: Icon(Icons.delete),
              onPressed: () {
                // Call the deleteReport method when the delete button is pressed
                deleteReport(report.roomId);  // Access 'id' using the getter
              },
            ),
          );
        },
      ),
    );
  }
}
