import 'package:shared_preferences/shared_preferences.dart';
import '../Controller/connection.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;

class AnnouncementModel {
  String title;
  String content;
  String picture;

  // constructor
  AnnouncementModel({
    required this.title,
    required this.content,
    required this.picture
  });

  // factory method to create an announcement from a map
  factory AnnouncementModel.fromJson(Map<String, dynamic> json) {
    return AnnouncementModel(
        title: json['title'] ?? '',
        content: json['content'] ?? '',
        picture: json['picture'] ?? ''
    );
  }

  // convert the announcement instance to a map
  Map<String, dynamic> toJson() {
    return {
      'title': title,
      'content': content,
      'picture': picture,
    };
  }


  Future<List<AnnouncementModel>> getAnnouncements() async {
    var prefs = await SharedPreferences.getInstance();
    String? token = prefs.getString('token');
    String? libraryId = prefs.getString('libraryID');
    final url = Uri.parse(API.announcement);

    try {
      final response = await http.get(
        url,
        headers: {
          'Authorization': 'Bearer $token',
        },
      );
      print(response.body);

      if (response.statusCode == 200) {
        dynamic responseBody = json.decode(response.body);
        print(responseBody);

        if (responseBody is Map<String, dynamic> &&
            responseBody.containsKey('data')) {
          List<AnnouncementModel> announcements = List<AnnouncementModel>.from(
            responseBody['data'].map(
                  (announcementData) => AnnouncementModel.fromJson(announcementData),
            ),
          );
          return announcements;
        } else {
          print('Not JSON body');
          return [];
        }
      } else {
        print('Error, status code: ${response.statusCode}');
        throw Exception('Status code: ${response.statusCode}');
      }
    } catch (ex) {
      print("Error :: $ex");
      throw Exception('Error: $ex');
    }
  }
}