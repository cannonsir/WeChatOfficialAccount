<template>
  <div class="wrap">
    <div class="head">
      <el-button @click="navigateToList">返回</el-button>
    </div>

    <el-divider content-position="left">菜单显示对象</el-divider>

    <el-form :inline="true" :model="menu.matchrule" class="demo-form-inline">
      <el-form-item label="标签">
        <el-select v-model="menu.matchrule.tag_id" placeholder="请选择标签" :disabled="disabled">
          <el-option v-for="tag of tags" :key="tag.id" :label="tag.name" :value="tag.id" />
        </el-select>
      </el-form-item>

      <el-form-item label="性别">
        <el-radio-group v-model="menu.matchrule.sex" :disabled="disabled">
          <el-radio-button label="">不限</el-radio-button>
          <el-radio-button label="1">男</el-radio-button>
          <el-radio-button label="2">女</el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item label="手机系统">
        <el-radio-group v-model="menu.matchrule.client_platform_type" :disabled="disabled">
          <el-radio-button label="">不限</el-radio-button>
          <el-radio-button label="1">IOS(苹果)</el-radio-button>
          <el-radio-button label="2">Android(安卓)</el-radio-button>
          <el-radio-button label="3">Others(其他)</el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item label="语言">
        <el-select v-model="menu.matchrule.language" placeholder="请选择语言" :disabled="disabled">
          <el-option v-for="{ label, value } of languages" :key="value" :label="label" :value="value" />
        </el-select>
      </el-form-item>

      <el-form-item label="地区">
          <el-cascader v-model="addressValue" :options="addresses" :props="{ checkStrictly: true }" :disabled="disabled"/>
      </el-form-item>

    </el-form>

    <we-chat-menu-editor ref="weChatMenuEditor" v-model="menu.button" :readonly="disabled" />

    <el-button v-if="!disabled" round style="width: 100%; margin-bottom: 10px;" @click="handleSubmit">新增</el-button>
  </div>
</template>

<script>
import WeChatMenuEditor from "../../components/WeChatMenuEditor";
import { index } from "../../../api/user_tag";
import { store } from "../../../api/menu";
import { languages, addresses } from "../../../enums/menu";

export default {
  name: "IndividuationMenu",
  components: {WeChatMenuEditor},
  inject: ['account'],
  props: {
    default: {
      type: Object,
      required: false
    },
    disabled: {
      type: Boolean,
      required: false
    }
  },
  data() {
    return {
      menu: {
        button: [],
        matchrule: {
          tag_id: '',
          sex: '',
          country: '',
          province: '',
          city: '',
          client_platform_type: '',
          language: ''
        }
      },
      tags: [
        {
          id: '',
          name: '不限',
          count: 0
        }
      ]
    }
  },
  computed: {
    languages() {
      return languages
    },
    addresses() {
      return addresses
    },
    addressValue: {
      get() {
        const rule = this.menu.matchrule
        const value = [rule.country]

        rule.province && value.push(rule.province)
        rule.city && value.push(rule.city)

        return value
      },
      set(address) {
        this.menu.matchrule.country = address[0] || ''
        this.menu.matchrule.province = address[1] || ''
        this.menu.matchrule.city = address[2] || ''
      }
    }
  },
  watch: {
    default: {
      immediate: true,
      deep: true,
      handler(menu) {
        if (typeof menu === 'object') {
          const copy = _.cloneDeep(menu)
          copy.matchrule.tag_id = copy.matchrule.tag_id || ''
          copy.matchrule.sex = copy.matchrule.sex || ''
          copy.matchrule.country = copy.matchrule.country || ''
          copy.matchrule.province = copy.matchrule.province || ''
          copy.matchrule.city = copy.matchrule.city || ''
          copy.matchrule.client_platform_type = copy.matchrule.client_platform_type || ''
          copy.matchrule.language = copy.matchrule.language || ''

          this.menu = copy
        }
      }
    }
  },
  created() {
    this.fetchTags()
  },
  methods: {
    async fetchTags() {
      try {
        const { tags } = await index(this.account.id)
        tags.unshift({
          id: '',
          name: '不限',
          count: 0
        })
        this.tags = tags
      } catch (e) {
        console.error(e)
        this.$message.error('获取标签数据失败')
      }
    },
    navigateToList() {
      this.$router.push({ name: 'WeChatOfficialAccount.menu' })
    },
    async handleSubmit() {
      if (!this.$refs.weChatMenuEditor.validate()) return this.$message.error('请检查参数')

      if (Object.keys(this.menu.matchrule).reduce((res, key) => res + this.menu.matchrule[key], '') === '') return this.$message.error('对象规则至少设定一项')

      if (this.menu.button.length === 0) return this.$message.error('未设置菜单')

      const loading = this.$loading({ text: '新增中,请稍等...' })

      try {
        await store(this.account.id, this.menu)

        this.$message.success('保存成功')
        this.navigateToList()
      } catch (e) {
        console.error(e)
        this.$message.error('保存失败')
      } finally {
        loading.close()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.wrap {
  .head {
    margin-bottom: 15px;
  }
}
</style>
