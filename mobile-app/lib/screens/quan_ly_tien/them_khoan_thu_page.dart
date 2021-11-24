import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/api/quan_ly_tien_api.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/components/nut_bam.dart';
import 'package:quan_ly_chi_tieu_ca_nhan/utils/constants.dart';

class ThemKhoanThuPage extends StatefulWidget {
  final int idQuanLyTien;
  final Function onSuccess;

  ThemKhoanThuPage({this.idQuanLyTien, this.onSuccess});

  @override
  _ThemKhoanThuPageState createState() => _ThemKhoanThuPageState();
}

class _ThemKhoanThuPageState extends State<ThemKhoanThuPage> {
  int soTien = 0;
  String nguonThu = "Gia đình";
  List<dynamic> incomeCategories = [];

  QuanLyTienApi quanLyTienApi = QuanLyTienApi();

  getIncommingData() async {
    var data = await quanLyTienApi.getAllIncomesCategory();

    setState(() {
      incomeCategories = data;
    });
  }

  @override
  void initState() {
    super.initState();
    getIncommingData();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Thêm nguồn thu'),
      ),
      body: SafeArea(
        child: Container(
          padding: kPaddingMainPage,
          color: Colors.white,
          child: ListView(
            children: [
              Text(
                'Nhập số tiền:',
                style: kTitleTextStyle,
              ),
              SizedBox(height: 10.0),
              TextField(
                textAlign: TextAlign.center,
                keyboardType: TextInputType.number,
                decoration: kTextFieldDecoration.copyWith(
                  hintText: 'Nhập số tiền',
                ),
                onChanged: (value) {
                  soTien = int.tryParse(value);
                },
              ),
              SizedBox(height: 30.0),
              Container(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Chọn nguồn thu:',
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
                          children: incomeCategories
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
                                                          incomeCategories
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
                                            groupValue: nguonThu,
                                            onChanged: (value) {
                                              setState(() {
                                                nguonThu = value;
                                              });
                                            },
                                          ),
                                        ],
                                      ))))
                              .values
                              .toList(),
                        ),
                      ),
                    )
                  ],
                ),
              ),
              SizedBox(height: 30.0),
              NutBam(
                textName: 'Thêm',
                onPressed: () async {
                  bool ketQua = await quanLyTienApi.themNguonThu(
                    idQuanLyTien: widget.idQuanLyTien,
                    soTien: soTien,
                    nhom: nguonThu,
                  );

                  if (ketQua == true) {
                    if (widget.onSuccess != null) widget.onSuccess();
                    Navigator.pop(context);
                  } else
                    print("lỗi");
                },
              )
            ],
          ),
        ),
      ),
    );
  }
}
