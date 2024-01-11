import 'package:shared_preferences/shared_preferences.dart';

class AuthService {
  static Future<bool> checkAuthenticationStatus() async {
    var preferences = await SharedPreferences.getInstance();
    var token = preferences.getString('token');
    return token != null && token.isNotEmpty;
  }

  static Future<void> saveTokenToSharedPreferences(String token) async {
    try {
      var preferences = await SharedPreferences.getInstance();
      await preferences.setString('token', token);
    } catch (e) {
      print("Error saving token to SharedPreferences: $e");
    }
  }

  static Future<void> logout() async {
    try {
      var preferences = await SharedPreferences.getInstance();
      await preferences.remove('token');
    } catch (e) {
      print("Error removing token from SharedPreferences: $e");
    }
  }
}
