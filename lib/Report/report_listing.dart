import 'dart:convert';
import 'package:cafe_library_services/Model/report_model.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../Controller/connection.dart';
import '../Welcome/home.dart';

class ReportListing extends StatelessWidget {
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
              return ReportListPage(libraryId: libraryId);
            }
          } else {
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

class ReportListPage extends StatefulWidget {
  final String libraryId;
  final Map<String, String>? headers;

  const ReportListPage({Key? key, required this.libraryId, this.headers})
      : super(key: key);

  @override
  _ReportListPageState createState() => _ReportListPageState();
}

class _ReportListPageState extends State<ReportListPage> {
  List<ReportModel> reports = [];

  @override
  void initState() {
    super.initState();
    // Fetch the submitted reports when the widget is initialized
    fetchReports().then((List<ReportModel> result) {
      setState(() {
        reports = result;
      });
    });
  }

  Future<List<ReportModel>> fetchReports() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String token = prefs.getString('token') ?? '';

    try {
      var headers = {
        "Content-Type": "application/json",
        'Authorization': 'Bearer $token',
      };

      var response = await http.get(Uri.parse(API.reportListing), headers:
      headers);

      if (response.statusCode == 200) {
        try {
          Map<String, dynamic> result = jsonDecode(response.body);

          if (result['status'] == 'success' &&
              result['data'] is Map &&
              result['data']['aaData'] is List) {
            List<dynamic> aaDataList = result['data']['aaData'];
            List<ReportModel> bookingRecords = [];

            for (var bookingData in aaDataList) {
              ReportModel booking = ReportModel.fromJson(bookingData);

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

      var response = await http.delete(Uri.parse('${API.deleteReport}'
          '?report_id=$reportId'), headers: headers);

      if (response.statusCode == 200) {
        await fetchReports();
      } else {
        print('Error deleting report. StatusCode: ${response.statusCode}, '
            'Reason: ${response.reasonPhrase}');
      }
    } catch (error) {
      print('Error: $error');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Submitted Reports'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(
                builder: (context) => const HomePage(libraryId: ''),
              ),
            );
          },
        ),
      ),
      body: ListView.builder(
        itemCount: reports.length,
        itemBuilder: (context, index) {
          var report = reports[index];
          return ListTile(
            title: Text(report.name),
            subtitle: Text(report.description),
            trailing: IconButton(
              icon: const Icon(Icons.delete),
              onPressed: () {
                showDialog(
                  context: context,
                  builder: (BuildContext context) {
                    return AlertDialog(
                      title: const Text('Confirm Delete'),
                      content: const Text('Are you sure you want to delete '
                          'this report?'),
                      actions: <Widget>[
                        TextButton(
                          onPressed: () {
                            Navigator.of(context).pop(); // Close the dialog
                          },
                          child: Text('Cancel'),
                        ),
                        TextButton(
                          onPressed: () async {
                            // Perform the delete action
                            //await deleteReport(report.id);
                            Navigator.of(context).pop(); // Close the dialog
                          },
                          child: Text('Delete'),
                        ),
                      ],
                    );
                  },
                );
              },
            ),
          );
        },
      ),
    );
  }
}
