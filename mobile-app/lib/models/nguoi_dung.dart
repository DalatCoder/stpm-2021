class NguoiDung {
  int id;
  String tenDangNhap;
  String matKhau;
  String tenHienThi;
  String avatar;
  DateTime createdAt;
  String sid;

  NguoiDung(
      {this.id,
      this.tenDangNhap,
      this.matKhau,
      this.tenHienThi,
      this.avatar,
      this.createdAt,
      this.sid});

  factory NguoiDung.fromJson(Map<String, dynamic> json, [String sid = '']) {
    return NguoiDung(
        id: json['id'],
        tenDangNhap: json['username'],
        matKhau: json[''],
        tenHienThi: json['display_name'],
        avatar: json['avatar'],
        createdAt: DateTime.parse(json['created_at']),
        sid: sid);
  }
}
