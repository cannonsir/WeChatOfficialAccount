import Vue from 'vue'
import _ from 'lodash'

const viewRules = {
  url: { required: true, message: '请输入链接地址' }
}

const miniprogramRules = {
  appid: { required: true, message: '请填写小程序APPID', trigger: 'change' },
  pagepath: { required: true, message: '请填写小程序页面路径' },
  url: { required: true, message: '请填写备用链接地址' }
}

const callbackRules = {
  key: { required: true, message: '请填写事件推送KEY' }
}

const mediaRules = {
  media_id: { required: true, message: '请填写微信素材ID' }
}

export const btnTypes = [
  {
    label: '跳转小程序',
    value: 'miniprogram',
    desc: '跳转至小程序页面',
    rules: miniprogramRules
  },
  {
    label: '触发回调',
    value: 'click',
    desc: '点击推事件用户点击click类型按钮后，微信服务器会通过消息接口推送消息类型为event的结构给开发者（参考消息接口指南），并且带上按钮中开发者填写的key值，开发者可以通过自定义的key值与用户进行交互；',
    rules: callbackRules
  },
  {
    label: '跳转网页',
    value: 'view',
    desc: '跳转URL用户点击view类型按钮后，微信客户端将会打开开发者在按钮中填写的网页URL，可与网页授权获取用户基本信息接口结合，获得用户基本信息。',
    rules: viewRules
  },
  {
    label: '扫码回调',
    value: 'scancode_push',
    desc: '扫码推事件用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后显示扫描结果（如果是URL，将进入URL），且会将扫码的结果传给开发者，开发者可以下发消息。',
    rules: callbackRules
  },
  {
    label: '扫码回调+提示',
    value: 'scancode_waitmsg',
    desc: '扫码推事件且弹出“消息接收中”提示框用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后，将扫码的结果传给开发者，同时收起扫一扫工具，然后弹出“消息接收中”提示框，随后可能会收到开发者下发的消息。',
    rules: callbackRules
  },
  {
    label: '拍照回调',
    value: 'pic_sysphoto',
    desc: '弹出系统拍照发图用户点击按钮后，微信客户端将调起系统相机，完成拍照操作后，会将拍摄的相片发送给开发者，并推送事件给开发者，同时收起系统相机，随后可能会收到开发者下发的消息。',
    rules: callbackRules
  },
  {
    label: '拍照/相册 回调',
    value: 'pic_photo_or_album',
    desc: '弹出拍照或者相册发图用户点击按钮后，微信客户端将弹出选择器供用户选择“拍照”或者“从手机相册选择”。用户选择后即走其他两种流程。',
    rules: callbackRules
  },
  {
    label: '微信相册回调',
    value: 'pic_weixin',
    desc: '弹出微信相册发图器用户点击按钮后，微信客户端将调起微信相册，完成选择操作后，将选择的相片发送给开发者的服务器，并推送事件给开发者，同时收起相册，随后可能会收到开发者下发的消息。',
    rules: callbackRules
  },
  {
    label: '地址选择回调',
    value: 'location_select',
    desc: '弹出地理位置选择器用户点击按钮后，微信客户端将调起地理位置选择工具，完成选择操作后，将选择的地理位置发送给开发者的服务器，同时收起位置选择工具，随后可能会收到开发者下发的消息。',
    rules: callbackRules
  },
  {
    label: '下发素材',
    value: 'media_id',
    desc: '下发消息（除文本消息）用户点击media_id类型按钮后，微信服务器会将开发者填写的永久素材id对应的素材下发给用户，永久素材类型可以是图片、音频、视频、图文消息。请注意：永久素材id必须是在“素材管理/新增永久素材”接口上传后获得的合法id。',
    rules: mediaRules
  },
  {
    label: '打开素材链接',
    value: 'view_limited',
    desc: '跳转图文消息URL用户点击view_limited类型按钮后，微信客户端将打开开发者在按钮中填写的永久素材id对应的图文消息URL，永久素材类型只支持图文消息。请注意：永久素材id必须是在“素材管理/新增永久素材”接口上传后获得的合法id。​',
    rules: mediaRules
  }
]

export default Vue.component('dynamic-type-input', {
  props: {
    type: {
      type: String,
      required: true,
      validator: val => btnTypes.map(obj => (obj.value)).includes(val)
    },
    value: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      button: {},
      rules: {}
    }
  },
  computed: {
    btnTypes() {
      return btnTypes
    }
  },
  watch: {
    value: {
      immediate: true,
      deep: true,
      handler(value) {
        this.button = value || {}
      }
    },
    button: {
      immediate: true,
      deep: true,
      handler(button) {
        this.$emit('input', button)
      }
    },
    type: {
      immediate: true,
      handler(type) {
        const item = _.find(btnTypes, { value: type }) || {}
        this.$emit('update:rules', item.rules || {})
      }
    }
  },
  render(h) {
    if (this.type === 'view') {
      return (
        <el-form-item label='链接地址' prop='url'>
          <el-input vModel={this.button.url} clearable placeholder='https://' />
        </el-form-item>
      )
    }

    if (this.type === 'miniprogram') {
      return (
        <div>
          <el-form-item label='小程序APPID' prop='appid'>
            <el-input vModel={this.button.appid} clearable placeholder='your app_id'/>
          </el-form-item>
          <el-form-item label='小程序页面路径' prop='pagepath'>
            <el-input vModel={this.button.pagepath} clearable placeholder='pages/home'/>
          </el-form-item>
          <el-form-item label='备用链接地址' prop='url'>
            <el-input vModel={this.button.url} clearable placeholder='https://'/>
          </el-form-item>
        </div>
      )
    }

    if (
      [
        'click', 'scancode_push', 'scancode_waitmsg', 'pic_sysphoto',
        'pic_photo_or_album', 'pic_weixin', 'location_select'
      ].includes(this.type)
    ) {
      return (
        <el-form-item label='标识符(key)' prop='key'>
          <el-input vModel={this.button.key} placeholder='菜单KEY值，用于消息接口推送，不超过128字节'/>
        </el-form-item>
      )
    }

    if (['media_id', 'view_limited'].includes(this.type)) {
      return (
        <el-form-item label='素材id' prop='media_id'>
          <el-input vModel={this.button.media_id} placeholder='media_id'/>
        </el-form-item>
      )
    }

    return null
  }
})
