class API {
  // this hostConnect is depends on ip address and file name and location

  static const hostConnect = "http://192.168.0.120/cafeLibraryServices-develop/public/api";
  //static const hostConnect = "https://cafelibraryservices.redpeople.io/api";

  // POST
  static const hostConnectUser = "$hostConnect";
  static const register = "$hostConnect/register";
  static const login = "$hostConnect/login";
  static const report = "$hostConnect/report";
  static const rent = "$hostConnect/booking";

  // GET
  static const announcement = "$hostConnect/announcement_listing";
  static const book = "$hostConnect/book_listing";
  static const beverage = "$hostConnect/beverage_listing";
  static const equipment = "$hostConnect/equipment_listing";
  static const room = "$hostConnect/room_listing";
  static const table = "$hostConnect/table_listing";
  static const booking = "$hostConnect/booking_listing";
  static const bookingRecord = "$hostConnect/booking/";
  static const ordering = "$hostConnect/order_listing";
  static const orderRecord = "$hostConnect/order/";
  static const reportListing = "$hostConnect/report_listing";

  // DELETE
  static const deleteReport = "$hostConnect/report/";

}