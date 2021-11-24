import 'package:flutter/material.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/api/chi_tieu_api.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/api/quan_ly_tien_api.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/components/nut_bam.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/utils/constants.dart';

class ThemChiTieuPage extends StatefulWidget {
  final int idNguoiDung;
  final Function onSuccess;

  ThemChiTieuPage({@required this.idNguoiDung, this.onSuccess});

  @override
  _ThemChiTieuPageState createState() => _ThemChiTieuPageState();
}

class _ThemChiTieuPageState extends State<ThemChiTieuPage> {
  String tenChiTieu = "";
  String nhom = "Thức ăn";
  String wallet_id = "";
  int soTien = 0;
  List<dynamic> outcomeCategories = [];
  List<dynamic> walletOptions = [];

  ChiTieuAPI chiTieuAPI = ChiTieuAPI();
  QuanLyTienApi quanLyTienApi = QuanLyTienApi();

  getOutcomeCategories() async {
    var data = await quanLyTienApi.getAllOutcomesCategory();
    var wallets = await quanLyTienApi.getAllWalletsByUserId(widget.idNguoiDung);

    setState(() {
      outcomeCategories = data;
      walletOptions = wallets;
    });
  }

  @override
  void initState() {
    super.initState();

    getOutcomeCategories();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Thêm 1 chi tiêu mới'),
      ),
      body: SafeArea(
        child: Container(
          color: Colors.white,
          padding: kPaddingMainPage,
          child: ListView(
            padding: EdgeInsets.symmetric(horizontal: 20.0, vertical: 10.0),
            children: [
              Container(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Tiêu đề:',
                      style: kTitleTextStyle,
                    ),
                    SizedBox(height: 10.0),
                    TextField(
                      textAlign: TextAlign.center,
                      decoration: kTextFieldDecoration.copyWith(
                        hintText: 'Nhập tiêu đề khoản chi',
                      ),
                      onChanged: (value) {
                        tenChiTieu = value;
                      },
                    )
                  ],
                ),
              ),
              SizedBox(height: 30.0),
              Container(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Nhóm chi tiêu:',
                      style: kTitleTextStyle,
                    ),
                    SizedBox(height: 15.0),
                    Container(
                      decoration: BoxDecoration(
                        border: Border(
                          top: BorderSide(width: 1.0, color: Colors.grey),
                          bottom: BorderSide(width: 1.0, color: Colors.grey),
                          left: BorderSide(width: 1.0, color: Colors.grey),
                          right: BorderSide(width: 1.0, color: Colors.grey),
                        ),
                        borderRadius: BorderRadius.circular(15.0),
                      ),
                      padding: EdgeInsets.all(15.0),
                      child: Card(
                        elevation: 0.0,
                        color: Colors.white,
                        child: Column(
                            children: outcomeCategories
                                .asMap()
                                .map((key, value) => MapEntry(
                                    key,
                                    Container(
                                        padding: const EdgeInsets.symmetric(
                                            vertical: 12),
                                        decoration: BoxDecoration(
                                            border: Border(
                                                bottom: BorderSide(
                                                    color: key <
                                                            outcomeCategories
                                                                    .length -
                                                                1
                                                        ? Colors.grey
                                                        : Colors.transparent,
                                                    width: 1.0))),
                                        child: Row(
                                          mainAxisAlignment:
                                              MainAxisAlignment.spaceBetween,
                                          children: [
                                            Container(
                                              height: 30.0,
                                              width: 7.0,
                                              margin: const EdgeInsets.only(
                                                  right: 12),
                                              decoration: BoxDecoration(
                                                color: Colors.blueAccent,
                                                borderRadius:
                                                    BorderRadius.circular(10.0),
                                              ),
                                            ),
                                            Text(
                                              value["name"],
                                              style: kNguonThuItemStyle,
                                            ),
                                            Spacer(),
                                            Radio(
                                              value: value["id"],
                                              groupValue: nhom,
                                              onChanged: (value) {
                                                setState(() {
                                                  nhom = value;
                                                });
                                              },
                                            ),
                                          ],
                                        ))))
                                .values
                                .toList()),
                      ),
                    ),
                    SizedBox(height: 15.0),
                  ],
                ),
              ),
              SizedBox(height: 30.0),
              Container(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Chọn kế hoạch chi tiêu',
                      style: kTitleTextStyle,
                    ),
                    SizedBox(height: 15.0),
                    Container(
                      decoration: BoxDecoration(
                        border: Border(
                          top: BorderSide(width: 1.0, color: Colors.grey),
                          bottom: BorderSide(width: 1.0, color: Colors.grey),
                          left: BorderSide(width: 1.0, color: Colors.grey),
                          right: BorderSide(width: 1.0, color: Colors.grey),
                        ),
                        borderRadius: BorderRadius.circular(15.0),
                      ),
                      padding: EdgeInsets.all(15.0),
                      child: Card(
                        elevation: 0.0,
                        color: Colors.white,
                        child: Column(
                            children: walletOptions
                                .asMap()
                                .map((key, value) => MapEntry(
                                    key,
                                    Container(
                                        padding: const EdgeInsets.symmetric(
                                            vertical: 12),
                                        decoration: BoxDecoration(
                                            border: Border(
                                                bottom: BorderSide(
                                                    color: key <
                                                            walletOptions
                                                                    .length -
                                                                1
                                                        ? Colors.grey
                                                        : Colors.transparent,
                                                    width: 1.0))),
                                        child: Row(
                                          mainAxisAlignment:
                                              MainAxisAlignment.spaceBetween,
                                          children: [
                                            Container(
                                              height: 30.0,
                                              width: 7.0,
                                              margin: const EdgeInsets.only(
                                                  right: 12),
                                              decoration: BoxDecoration(
                                                color: Colors.blueAccent,
                                                borderRadius:
                                                    BorderRadius.circular(10.0),
                                              ),
                                            ),
                                            Text(
                                              value["date_begin"],
                                              style: kNguonThuItemStyle,
                                            ),
                                            Spacer(),
                                            Radio(
                                              value: value["id"],
                                              groupValue: wallet_id,
                                              onChanged: (value) {
                                                setState(() {
                                                  wallet_id = value;
                                                });
                                              },
                                            ),
                                          ],
                                        ))))
                                .values
                                .toList()),
                      ),
                    ),
                    SizedBox(height: 15.0),
                  ],
                ),
              ),
              SizedBox(height: 30.0),
              Container(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Số tiền:',
                      style: kTitleTextStyle,
                    ),
                    SizedBox(height: 10.0),
                    TextField(
                      keyboardType: TextInputType.number,
                      textAlign: TextAlign.center,
                      decoration: kTextFieldDecoration.copyWith(
                        hintText: 'Nhập số tiền đã chi',
                      ),
                      onChanged: (value) {
                        soTien = int.parse(value);
                      },
                    )
                  ],
                ),
              ),
              SizedBox(height: 30.0),
              NutBam(
                textName: 'Thêm chi tiêu',
                onPressed: () async {
                  bool ketQua = await chiTieuAPI.themChiTieu(
                      tenChiTieu: tenChiTieu,
                      nhom: nhom,
                      soTien: soTien,
                      ngayChiTieu: DateTime.now(),
                      idNguoiDung: widget.idNguoiDung,
                      walletId: wallet_id);
                  if (ketQua == true) {
                    if (widget.onSuccess != null) widget.onSuccess();
                    Navigator.pop(context);
                  } else
                    print("lỗi");
                },
              ),
            ],
          ),
        ),
      ),
    );
  }
}
