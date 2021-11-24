class ListQuanLyTien {
  int id;
  DateTime ngayBD;
  DateTime ngayKT;
  bool trangThai;

  ListQuanLyTien({
    this.id,
    this.ngayBD,
    this.ngayKT,
    this.trangThai,
  });

  factory ListQuanLyTien.fromJson(Map<String, dynamic> json) {
    return ListQuanLyTien(
        id: int.tryParse(json['id']),
        ngayBD: DateTime.parse(json['date_begin']),
        ngayKT: DateTime.parse(json['date_end']),
        trangThai: false);
  }
}
