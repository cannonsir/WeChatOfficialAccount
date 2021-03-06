<template>
  <div class="event-assign">
    <div class="wrap">
      <div class="head">
        <span class="title">{{ btn.name }}</span>

        <el-button v-if="!readonly" type="danger" size="small" @click="$emit('destroy')">删除菜单</el-button>
      </div>
      <div class="content">
        <el-form ref="form" class="form" :rules="allRules" :model="btn" label-width="130px">
          <el-form-item label="菜单名称" prop="name">
            <el-input :readonly="readonly" v-model="btn.name" clearable placeholder="一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替,菜单不超过16个字节，子菜单不超过60个字节" />
          </el-form-item>
          <div v-show="!btn.sub_button || btn.sub_button.length === 0">
            <el-form-item label="按钮类型" prop="type">
              <el-select :disabled="readonly" v-model="btn.type" placeholder="请选择按钮类型" @change="handleToggleType">
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
                <el-input :readonly="readonly" v-model="btn.url" clearable placeholder='https://' />
              </el-form-item>
            </div>

            <div v-if="btn.type === 'miniprogram'">
              <el-form-item label='小程序APPID' prop='appid'>
                <el-input :readonly="readonly" v-model="btn.appid" clearable placeholder='your app_id'/>
              </el-form-item>
              <el-form-item label='小程序页面路径' prop='pagepath'>
                <el-input :readonly="readonly" v-model="btn.pagepath" clearable placeholder='pages/home'/>
              </el-form-item>
              <el-form-item label='备用链接地址' prop='url'>
                <el-input :readonly="readonly" v-model="btn.url" clearable placeholder='https://'/>
              </el-form-item>
            </div>

            <div v-if="['click', 'scancode_push', 'scancode_waitmsg', 'pic_sysphoto','pic_photo_or_album', 'pic_weixin', 'location_select'].includes(btn.type)">
              <el-form-item label='标识符(key)' prop='key'>
                <el-input :readonly="readonly" v-model="btn.key" placeholder='菜单KEY值，用于消息接口推送，不超过128字节'/>
              </el-form-item>
            </div>

            <div v-if="['media_id', 'view_limited'].includes(btn.type)">
              <el-form-item label='素材id' prop='media_id'>
                <div style="display:flex;">
                  <el-input :readonly="readonly" v-model="btn.media_id" placeholder='media_id'/>
                  <el-button @click="materialDialogVisible = true">选择</el-button>
                </div>
              </el-form-item>
            </div>
          </div>
        </el-form>
      </div>

      <el-dialog
        title="提示"
        :visible.sync="materialDialogVisible"
        width="60%"
      >
        <material @click="toggleSelectedMaterial"></material>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'
import DynamicTypeInput from './DynamicTypeInput'
import { allowButtons } from './DynamicTypeInput'
import Material from "../account/material";

export default {
  name: 'EventAssign',
  components: {Material, DynamicTypeInput },
  props: {
    value: {
      type: Object,
      required: true
    },
    readonly: {
      type: Boolean,
      required: false
    }
  },
  data() {
    return {
      btn: {},
      materialDialogVisible: false
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
      return (_.find(this.allowButtons, { value: this.btn.type }) || {}).rules || {}
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
    formValidate() {
      let valid = false

      this.$refs.form.validate(bool => (valid = bool))

      return valid
    },
    toggleSelectedMaterial(type, item) {
      this.$set(this.btn, 'media_id', item.media_id)
      this.materialDialogVisible = false
    }
  }
}
</script>

<style lang="scss" scoped>
.event-assign {
  height: 100%;
  width: 100%;
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
    height: 100%;
    .head, .content {
      padding: 12px 0;
    }
    .content {
      height: 100%;
      overflow: auto;
      &::-webkit-scrollbar {
        width: 3px;     /*高宽分别对应横竖滚动条的尺寸*/
        height: 1px;
      }
      &::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
        background: #c2d7f0;
      }
      &::-webkit-scrollbar-track {
        border-radius: 10px;
      }
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
