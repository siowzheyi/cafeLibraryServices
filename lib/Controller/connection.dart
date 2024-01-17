class API {
  // this hostConnect is depends on ip address and file name and location

  //static const hostConnect = "http://192.168.0.120:8000/api";
  static const hostConnect = "http://192.168.0.120/cafeLibraryServices/public/api";
  //static const hostConnect = "https://cafelibraryservices.redpeople.io/api";

  // POST
  static const hostConnectUser = "$hostConnect";
  static const register = "$hostConnect/register";
  static const login = "$hostConnect/login";
  static const report = "$hostConnect/report";
  static const rent = "$hostConnect/booking";
  static const order = "$hostConnect/order";
  static const payment = "$hostConnect/payment";

  // GET
  static const announcement = "$hostConnect/announcement_listing";
  static const book = "$hostConnect/book_listing";
  static const beverage = "$hostConnect/beverage_listing";
  static const equipment = "$hostConnect/equipment_listing";
  static const room = "$hostConnect/room_listing";
  static const table = "$hostConnect/table_listing";
  static const booking = "$hostConnect/booking_listing";
  static const bookingRecord = "$hostConnect/booking";
  static const ordering = "$hostConnect/order_listing";
  static const orderRecord = "$hostConnect/order";
  static const reportListing = "$hostConnect/report_listing";
  static const paymentListing = "$hostConnect/payment";
  static const penaltyListing = "$hostConnect/penalty_report";
  static const penaltyReport = "$hostConnect/penalty_report_item";

  // DELETE
  static const deleteReport = "$hostConnect/report";

  // PATCH
  static const returnBooking = "$hostConnect/booking/return";

}