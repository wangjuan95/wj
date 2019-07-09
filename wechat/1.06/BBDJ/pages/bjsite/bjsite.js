// pages/bjsite/bjsite.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    user_id: '',
    username: '',
    phone: '',
    region: '请选择',
    xxsite: '',
    id: '',
  },
  submit(e) {
    var that = this
    var id = that.data.id
    var myreg = /^(14[0-9]|13[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$$/;
    var name = this.data.username
    var phone = this.data.phone
    var xxsite = this.data.xxsite
    var region = this.data.region
    var arr = []
    // console.log(this.data)
    if (name == '') {
      wx.showToast({
        title: '请输入姓名',
        icon: 'none',
        duration: 1000
      })
      return false
    } else if (phone == "") {
      wx.showToast({
        title: '手机号不能为空',
        icon: 'none',
        duration: 1000
      })
      return false;
    } else if (!myreg.test(phone)) {
      wx.showToast({
        title: '请输入正确的手机号',
        icon: 'none',
        duration: 1000
      })
      return false;
    } else if (region == '请选择') {
      wx.showToast({
        title: '请选择地区',
        icon: 'none',
        duration: 1000
      })
      return false
    } else if (xxsite == '') {
      wx.showToast({
        title: '请输入详细的地址',
        icon: 'none',
        duration: 1000
      })
      return false
    } else {
      // arr.push(name)
      // console.log(region[0]);
      wx.showToast({
        title: '保存成功',
        duration: 1000
      })
      wx.request({
        // 地址
        url: app.globalData.https + 'WxProgram/saveUserAddress',
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        // 参数
        data: {
          user_id: that.data.user_id,
          address_id: id, 
          realname: name,
          telephone: phone,
          province: region[0],
          city: region[1],
          area: region[2],
          address: xxsite
        },
        // 方式
        method: "post",
        // 请求成功
        success(res) {
          wx.navigateBack()
          // console.log(xxsite)
        },
        // 请求失败
        fail(res) {
          console.log("请求失败", res);
        }
      })

      // wx.setStorage({
      //   key: 'site',
      //   data: {
      //     name: name,
      //     phone: phone,   
      //     region: region,
      //     xxsite: xxsite
      //   },
      //   success(res) {
      //     // console.log(res)
      //       wx.navigateBack() //跳转页面的路径，可带参数 ？隔开，不同参数用 & 分隔；相对路径，不需要.wxml后缀
      //   }
      // })

    }

  },
  username(e) {
    this.setData({
      username: e.detail.value,
    });
  },
  phone(e) {
    this.setData({
      phone: e.detail.value,
    });
  },
  xxsite(e) {
    // console.log(e.detail.value)
    this.setData({
      xxsite: e.detail.value,
    });
  },
  bindRegionChange(e) {
    // console.log(e)
    this.setData({
      region: e.detail.value,
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    var id = options.id
    var user_id = wx.getStorageSync('user_id');
    that.setData({
      user_id: user_id
    })
    // console.log(id)
      wx.request({
        // 地址
        url: app.globalData.https + 'WxProgram/selectUserAddress',
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        // 参数
        data: {
          user_id: that.data.user_id,
          address_id: id
        },
        // 方式
        method: "post",
        // 请求成功
        success(res) {
          console.log(res);
          var data = res.data.data;
          that.setData(
            {
              username: data.realname,
              phone: data.telephone,
              id: id,
              region: [data.province, data.city, data.area],
              xxsite: data.address,

            }, 
            // {phone: data.telephone}, 
            // {xxsite: data.address}
          );
        },
        // 请求失败
        fail(res) {
          console.log("请求失败", res);
        }
      })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})