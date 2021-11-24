class ChiTieu {
  int id;
  int quanLyTienHienCoId;
  int tongChi;
  DateTime ngay;
  bool isSelected;

  ChiTieu({
    this.id = 0,
    this.quanLyTienHienCoId = 0,
    this.tongChi = 0,
    DateTime ngay,
    this.isSelected = false,
  }) : this.ngay = ngay ?? DateTime.now();

  factory ChiTieu.fromJson(Map<String, dynamic> json) {
    return ChiTieu(
      id: 0,
      quanLyTienHienCoId: int.tryParse(json['wallet_id']),
      tongChi: json['total'],
      ngay: DateTime.parse(json['date'].toString()),
    );
  }
}
