// pages/tzwater/tzwater.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    station_id: '',
    user_id: '',
    pic_array: [],
    hx_index: 0,
    tzwaterList: [],
    totalPrice: "0.00",
  },
  // 跳转
  account() {
    // var arr = []
    var that = this;
    var arr = []
    let list = this.data.tzwaterList;
    for (let i = 0; i < list.length; i++) {
      // var arr = list[i]
      // 判断选中计算价格
      if (list[i].num !== 0) {
        arr.push(list[i])
      // 所有价格加起来 count_money
        wx.setStorage({
          key: 'key',
          data: arr,
          // success(res) {
          //   console.log(arr)
          // }
          
        })
        // console.log(list[i])
      } 
    }
    // pic_array[hx_index].username
    console.log(that.data.pic_array.length)
    if (that.data.pic_array.length == 0) {
      wx.showToast({
        title: '请选择驿站',
        icon: 'none',
        duration: 1000
      })
      return false
    } else if (arr.length == 0) {
      wx.showToast({
        title: '请选择商品',
        icon: 'none',
        duration: 1000
      })
      return false
    } else {
      // console.log(that.data.station_id);
      wx.navigateTo({
        success(e) {
        },
        url: '/pages/makeWater/makeWater?id=' + that.data.station_id
      })
    }
    // if (arr.length == 0) {
    //   wx.showToast({
    //     title: '请选择商品',
    //     icon: 'none',
    //     duration: 1000
    //   })
    //   return false
    // } else {
    //   // console.log(that.data.station_id);
    //   wx.navigateTo({
    //     success(e) {
    //     },
    //     url: '/pages/makeWater/makeWater?id=' + that.data.station_id
    //   })
    // }
    
    
  },
  // 加
  addCount(e) {
    // console.log(e.currentTarget.dataset.index)
    var index = e.target.dataset.index;
    // console.log(this.data.tzwaterList[index]);
    this.data.tzwaterList[index].num = this.data.tzwaterList[index].num + 1;
    this.setData({
      tzwaterList: this.data.tzwaterList
    });
    this.view_a()
  },
  // 减
  minusCount(e) {
    var index = e.target.dataset.index;
    // console.log(this.data.tzwaterList[index]);
    
    this.data.tzwaterList[index].num = this.data.tzwaterList[index].num - 1;
    if (this.data.tzwaterList[index].num<0) {
      this.data.tzwaterList[index].num = 0
    }
      this.setData({
        tzwaterList: this.data.tzwaterList
      });
    this.view_a()
    
    
  },
  // 合计价
  view_a() {
    // 获取商品列表数据
    // console.log(this.data.tzwaterList)
    let list = this.data.tzwaterList;
    // console.log(list)
    // // 声明一个变量接收数组列表price
    let total = 0;
    // // 循环列表得到每个数据
    for (let i = 0; i < list.length; i++) {
      // 判断选中计算价格
      // if (list[i].selected) {
        // 所有价格加起来 count_money
        total += list[i].num * list[i].price;
      // }
    }
    // 最后赋值到data中渲染到页面
    this.setData({
      tzwaterList: list,
      totalPrice: total.toFixed(2)
    });
  },
  // 选择驿站
  bindPickerChange_hx: function (e) {
    var that = this;
    // console.log('picker发送选择改变,索引为', e.detail.value),
    var station_id = that.data.pic_array[e.detail.value].station_id
    that.setData({   //给变量赋值
      //根据索引去查对应的id
      hx_index: e.detail.value,  //每次选择了下拉列表的内容同时修改下标然后修改显示的内容,显示的内容和选择的内容一致
      station_id: station_id
    })
    console.log(station_id);
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    // 经纬度
    // 传参ID
    var id = options.id
    // 缓存ID
    var user_id = wx.getStorageSync('user_id')
    that.setData({
      user_id: user_id
    })
    wx.request({
      // 地址
      url: app.globalData.https + 'WxProgram/ServerCommodityGoods',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 参数
      data: {
        pid: id
      },
      // 方式
      method: "post",
      // 请求成功
      success(res) {
        // console.log(res);
        var data = res.data.data;
        that.setData({
          tzwaterList: data
        });
      },
      // 请求失败
      fail(res) {
        console.log("请求失败", res);
      }
    });
    // 经纬度
    wx.getLocation({
      type: 'gcj02',
      success(res) {
        var latitude = res.latitude
        var longitude = res.longitude
        wx.request({
          // 地址
          url: app.globalData.https + 'WxProgram/selectNearbyStation',
          header: { 'content-type': 'application/x-www-form-urlencoded' },
          // 参数
          data: {
            user_id: that.data.user_id,
            longitude: longitude,
            latitude: latitude
          },
          // 方式
          method: "post",
          // 请求成功
          success(res) {
            that.setData({
              pic_array: res.data.data,
              station_id: res.data.data[0].station_id
            })
          },
          // 请求失败
          fail(res) {
            console.log("请求失败", res);
          }
        })
      }
    })

    // 驿站
    
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