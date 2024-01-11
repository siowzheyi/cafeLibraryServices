class TableModel {
  String tableNo;

  // constructor
  TableModel({
    required this.tableNo
  });

  // factory method to create an equipment from a map
  factory TableModel.fromJson(Map<String, dynamic> json) {
    return TableModel(
        tableNo: json['table_no'] ?? ''
    );
  }

  // convert the equipment instance to a map
  Map<String, dynamic> toJson() {
    return {
      'table_no': tableNo
    };
  }
}