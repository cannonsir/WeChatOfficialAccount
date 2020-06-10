<template>
  <div class="event-assign">
    <div class="wrap">
      <div class="head">
        <span class="title">{{ btn.name }}</span>

        <el-button type="danger" size="small" @click="$emit('destroy')">删除菜单</el-button>
      </div>
      <div class="content">
        <el-form ref="form" class="form" :rules="allRules" :model="btn" label-width="130px">
          <el-form-item label="菜单名称" prop="name">
            <el-input v-model="btn.name" clearable placeholder="一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替,菜单不超过16个字节，子菜单不超过60个字节" />
          </el-form-item>
          <div v-show="!btn.sub_button || btn.sub_button.length === 0">
            <el-form-item label="按钮类型" prop="type">
              <el-select v-model="btn.type" placeholder="请选择按钮类型" @change="handleToggleType">
                <el-option v-for="type in allowButtons" :key="type.value" :label="type.label" :value="type.value" />
              </el-select>

              <el-popover
                v-if="btnTypeDesc"
                placement="top-start"
                width="500"
                trigger="hover"
                :content="btnTypeDesc"
              >
                <i slot="reference" class="el-icon-more" />
              </el-popover>
            </el-form-item>

            <el-divider />

            <div v-if="btn.type === 'view'">
              <el-form-item label='链接地址' prop='url'>
                <el-input v-model="btn.url" clearable placeholder='https://' />
              </el-form-item>
            </div>

            <div v-if="btn.type === 'miniprogram'">
              <el-form-item label='小程序APPID' prop='appid'>
                <el-input v-model="btn.appid" clearable placeholder='your app_id'/>
              </el-form-item>
              <el-form-item label='小程序页面路径' prop='pagepath'>
                <el-input v-model="btn.pagepath" clearable placeholder='pages/home'/>
              </el-form-item>
              <el-form-item label='备用链接地址' prop='url'>
                <el-input v-model="btn.url" clearable placeholder='https://'/>
              </el-form-item>
            </div>

            <div v-if="['click', 'scancode_push', 'scancode_waitmsg', 'pic_sysphoto','pic_photo_or_album', 'pic_weixin', 'location_select'].includes(btn.type)">
              <el-form-item label='标识符(key)' prop='key'>
                <el-input v-model="btn.key" placeholder='菜单KEY值，用于消息接口推送，不超过128字节'/>
              </el-form-item>
            </div>

            <div v-if="['media_id', 'view_limited'].includes(btn.type)">
              <el-form-item label='素材id' prop='media_id'>
                <el-input v-model="btn.media_id" placeholder='media_id'/>
              </el-form-item>
            </div>
          </div>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import DynamicTypeInput from './DynamicTypeInput'
import { allowButtons } from './DynamicTypeInput'

export default {
  name: 'EventAssign',
  components: { DynamicTypeInput },
  props: {
    value: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      btn: {}
    }
  },
  computed: {
    btnTypeDesc() {
      return (_.find(this.allowButtons, { value: this.btn.type || '' }) || {}).desc || ''
    },
    basicRules() {
      const rules = {
        name: { required: true, message: '请填写菜单名称' }
      }

      if (!this.btn.sub_button || this.btn.sub_button.length === 0) {
        rules.type = { required: true, message: '请选择按钮类型' }
      }

      return rules
    },
    allowButtons() {
      return allowButtons
    },
    dynamicTypeInputRules() {
      const item = _.find(this.allowButtons, { value: this.btn.type }) || {}
      this.$emit('update:rules', item.rules || {})
    },
    allRules() {
      return { ...this.basicRules, ...this.dynamicTypeInputRules }
    }
  },
  watch: {
    value: {
      immediate: true,
      deep: true,
      handler(value) {
        this.btn = _.cloneDeep(value)
      }
    },
    btn: {
      immediate: true,
      deep: true,
      handler(btn) {
        !_.isEqual(btn, this.value) && this.$emit('input', btn)
      }
    }
  },
  methods: {
    handleToggleType() {
      for (const key in this.btn) {
        if (!['id', 'type', 'name', 'sub_button'].includes(key)) {
          this.$delete(this.btn, key)
        }
      }
    },
    generateKey() {
      return Math.random().toString(36).slice(-8)
    },
    formValidate() {
      let valid = false

      this.$refs.form.validate(bool => (valid = bool))

      return valid
    }
  }
}
</script>

<style lang="scss" scoped>
.event-assign {
  height: 100%;
  width: 100%;
  border: 1px solid #e3e3e3;
  a {
    text-decoration: none;
    color: #1989fa;
    &:hover {
      color: #1696ff;
    }
    &.active {
      color: #1989fa;
    }
  }
  .wrap {
    display: flex;
    flex-direction: column;
    margin: 0 20px;
    .head, .content {
      padding: 12px 0;
    }
    .head {
      display: flex;
      border-bottom: 1px solid #e3e3e3;
      justify-content: space-between;
      align-items: center;
    }
  }
}
</style>
