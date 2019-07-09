// pages/wash/wash.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id: '',
    issue: [],
    article: [],
    headNav: ['标准收费', '常见问题', '评价'],
    headNavitem: '标准收费', 
    step: [
      {
        text: "01.上门评估",
        img: "http://bbsh-com.oss-cn-beijing.aliyuncs.com/xiaochengxu/WeChat_img/images/page_head01.png"
      }, {
        text: "02.确认衣服",
        img: "http://bbsh-com.oss-cn-beijing.aliyuncs.com/xiaochengxu/WeChat_img/images/page_head02.png"
      }, {
        text: "03.支付",
        img: "http://bbsh-com.oss-cn-beijing.aliyuncs.com/xiaochengxu/WeChat_img/images/page_head03.png"
      }, {
        text: "04.干洗中心",
        img: "http://bbsh-com.oss-cn-beijing.aliyuncs.com/xiaochengxu/WeChat_img/images/page_head04.png"
      }, {
        text: "05.送衣上门",
        img: "http://bbsh-com.oss-cn-beijing.aliyuncs.com/xiaochengxu/WeChat_img/images/page_head05.png"
      }, {
        text: "06.确认收货",
        img: "http://bbsh-com.oss-cn-beijing.aliyuncs.com/xiaochengxu/WeChat_img/images/page_head06.png"
      },
    ],
  },
  jumpTo: function (e) {
    // 获取标签元素上自定义的 data-opt 属性的值
    // console.log(e)
    let target = e.currentTarget.dataset.opt;
    var classify = e.currentTarget.dataset.classify;
    // var classify = res.currentTarget.dataset.classify;
    // coonsole.log(classify)
    // console.log(target)
    this.setData({
      headNavitem: classify,
      toView: target

    })
  },
  rate(e) {
    var item = e.currentTarget.dataset.item
    this.data.issue[item].ishide = !this.data.issue[item].ishide
    var issue = this.data.issue;
    this.setData({
      issue: issue
    })
  },
  // nav切换
  btn(e) {
    var id = this.data.id
    wx.navigateTo({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
      url: "/pages/washMake/washMake?id=" + id,
    })
    // console.log(e.currentTarget.id)
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    // 获取上一个页面参数ID
    var id = options.id
    that.setData({
      id: id
    })
    var user_id = wx.getStorageSync('user_id');
    wx.request({
      // 地址
      url: app.globalData.https + 'WxProgram/getCleaningGoods',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 参数
      data: {
        pid: 2,
        user_id: user_id
      },
      // 方式
      method: "post",
      // 请求成功
      success(res) {
        var data = res.data.data;
        that.setData({
          article: data
        });
      },
      // 请求失败
      fail(res) {
        console.log("请求失败", res);
      }
    })
    // 查询常见问题
    wx.request({
      // 地址
      url: app.globalData.https + 'WxProgram/selectCommonProblem',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 参数
      data: {
        pid: id
      },
      // 方式
      method: "post",
      // 请求成功
      success(res) {
        // console.log(that.data.id)
        // console.log(res.data.data);
        var data = res.data.data;
        that.setData({
          issue: data
        });
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