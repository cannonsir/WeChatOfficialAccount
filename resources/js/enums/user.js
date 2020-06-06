export const GENDER_MAN = 1

export const GENDER_WOMAN = 2

export const denders = [
  {
    label: '男',
    value: GENDER_MAN
  },
  {
    label: '女',
    value: GENDER_WOMAN
  }
]

export const SOURCE_ADD_SCENE_SEARCH = 'ADD_SCENE_SEARCH'
export const SOURCE_ADD_SCENE_ACCOUNT_MIGRATION = 'ADD_SCENE_ACCOUNT_MIGRATION'
export const SOURCE_ADD_SCENE_PROFILE_CARD = 'ADD_SCENE_PROFILE_CARD'
export const SOURCE_ADD_SCENE_QR_CODE = 'ADD_SCENE_QR_CODE'
export const SOURCE_ADD_SCENE_PROFILE_LINK = 'ADD_SCENE_PROFILE_LINK'
export const SOURCE_ADD_SCENE_PROFILE_ITEM = 'ADD_SCENE_PROFILE_ITEM'
export const SOURCE_ADD_SCENE_PAID = 'ADD_SCENE_PAID'
export const SOURCE_ADD_SCENE_OTHERS = 'ADD_SCENE_OTHERS'

export const sources = [
  {
    label: '公众号搜索',
    value: SOURCE_ADD_SCENE_SEARCH
  },
  {
    label: '公众号迁移',
    value: SOURCE_ADD_SCENE_ACCOUNT_MIGRATION
  },
  {
    label: '名片分享',
    value: SOURCE_ADD_SCENE_PROFILE_CARD
  },
  {
    label: '扫描二维码',
    value: SOURCE_ADD_SCENE_QR_CODE
  },
  {
    label: '图文页内名称点击',
    value: SOURCE_ADD_SCENE_PROFILE_LINK
  },
  {
    label: '图文页右上角菜单',
    value: SOURCE_ADD_SCENE_PROFILE_ITEM
  },
  {
    label: '支付后关注',
    value: SOURCE_ADD_SCENE_PAID
  },
  {
    label: '其他',
    value: SOURCE_ADD_SCENE_OTHERS
  }
]
