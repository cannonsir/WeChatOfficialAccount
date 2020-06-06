<template>
  <div class="preview">
    <div class="buttons">
      <div
        v-for="(btn, index) in buttons"
        :key="btn.id"
        class="button"
        :class="{ active: btn.id === selected }"
        @click="toggleSelected(btn.id)"
      >
        <div class="label">
          <i v-show="btn.sub_button && btn.sub_button.length > 0" class="el-icon-s-operation" />
          <span>{{ btn.name }}</span>
        </div>

        <div v-show="visibleSubButtonIndex === index" class="sub-buttons" @click.stop>
          <div
            v-for="sub_btn in btn.sub_button"
            :key="sub_btn.id"
            class="button"
            @click="toggleSelected(sub_btn.id)"
          >
            <div class="label" :class="{ active: sub_btn.id === selected }">{{ sub_btn.name | buttonTextEllipsis }}</div>
          </div>
          <div
            v-if="!btn.sub_button || btn.sub_button.length < 5"
            class="button add"
            @click="addSubButton(btn.id)"
          >
            <div class="label"><i class="el-icon-plus" /></div>
          </div>
          <div class="arrow" />
        </div>
      </div>
      <div v-if="buttons.length < 3" class="button" @click="addButton">
        <i class="el-icon-plus label" />
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'

export default {
  name: 'MobilePreview',
  filters: {
    buttonTextEllipsis(text) {
      const strlen = val => {
        var len = 0
        for (var i = 0; i < val.length; i++) {
          var length = val.charCodeAt(i)
          if (length >= 0 && length <= 128) {
            len += 1
          } else {
            len += 2
          }
        }
        return len
      }

      const substr = (str, start, n) => { // eslint-disable-line
        if (str.replace(/[\u4e00-\u9fa5]/g, '**').length <= n) {
          return str
        }
        let len = 0
        let tmpStr = ''
        for (let i = start; i < str.length; i++) { // 遍历字符串
          if (/[\u4e00-\u9fa5]/.test(str[i])) { // 中文 长度为两字节
            len += 2
          } else {
            len += 1
          }
          if (len > n) {
            break
          } else {
            tmpStr += str[i]
          }
        }
        return tmpStr
      }

      return strlen(text) > 14 ? substr(text, 0, 12) + '...' : text
    }
  },
  props: {
    value: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      buttons: this.value,
      selected: null
    }
  },
  computed: {
    visibleSubButtonIndex() {
      const id = this.selected

      for (const index in this.buttons) {
        const ref = this.buttons[index]

        if (ref.id === id) return Number(index)

        const findSubIndex = _.findIndex(ref.sub_button || [], { id })

        if (findSubIndex !== -1) return Number(index)
      }

      return null
    }
  },
  watch: {
    value: {
      immediate: true,
      deep: true,
      handler(button) {
        this.buttons = button
      }
    },
    buttons: {
      immediate: true,
      deep: true,
      handler(buttons) {
        this.$emit('input', buttons)
      }
    },
    selected(id) {
      this.$emit('selected-id-change', id)
    },
    visibleSubButtonIndex(index) {
      if (index === null && !!this.selected && this.buttons.length > 0) {
        this.selected = this.buttons[0].id
      }
    }
  },
  methods: {
    addButton() {
      this.buttons.push({
        id: Math.random().toString(36).slice(-8),
        name: '菜单名称'
      })
    },
    addSubButton(id) {
      const findIndex = _.findIndex(this.buttons, { id })

      this.$set(this.buttons[findIndex], 'sub_button', this.buttons[findIndex].sub_button || [])

      for (const key in this.buttons[findIndex]) {
        ['id', 'name', 'sub_button'].includes(key) || this.$delete(this.buttons[findIndex], key)
      }

      this.buttons[findIndex].sub_button.push({
        id: Math.random().toString(36).slice(-8),
        name: '子菜单名称'
      })
    },
    toggleSelected(id, force = false) {
      if (force) {
        this.selected = id
        return
      }

      const topBtnIndex = _.findIndex(this.buttons, { id })

      this.selected = topBtnIndex === -1
        ? id
        : topBtnIndex === this.visibleSubButtonIndex
          ? null
          : this.selected === id
            ? null
            : id
    }
  }
}
</script>

<style lang="scss" scoped>
$buttonBackground: #f9f9f9;
$borderColor: #e3e3e3;
$activeBorderColor: #7adb86;
$vWidth: 317px;
$vHeight: 580px;
$fontSize: 14px;

.preview {
  font-size: $fontSize;
  width: $vWidth;
  height: $vHeight;
  border: 1px solid $borderColor;
  position: relative;
  background: white;
  user-select: none;
  .buttons {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 50px;
    border-top: 1px solid $borderColor;
    width: 100%;
    display: flex;
    & > .button {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
      width: 100%;
      color: #747474;
      text-overflow: ellipsis;
      -o-text-overflow: ellipsis;
      white-space: nowrap;
      overflow: hidden;
      &:not(:last-of-type) {
        border-right: 1px solid $borderColor;
      }
      &.active .label{
        border: 2px solid $activeBorderColor;
      }

      .label {
        height: 100%;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        border: 2px solid $buttonBackground;
        span {
          text-overflow: ellipsis;
          -o-text-overflow: ellipsis;
          white-space: nowrap;
          overflow: hidden;
        }
        &:hover {
          color: #000;
        }
      }

      .sub-buttons {
        position: absolute;
        bottom: 55px;
        max-height: calc($vHeight - 50px);
        max-width: $vWidth;
        border: 1px solid $borderColor;
        display: flex;
        flex-direction: column;
        .button {
          display: flex;
          justify-content: center;
          height: 100%;
          width: 100%;
          &:not(:last-of-type) {
            border-bottom: 1px solid $borderColor;
          }
          &:hover {
            color: #000;
          }
          &.add {
            text-align: center;
            min-width: 5rem;
          }
          .label {
            height: 100%;
            width: 100%;
            cursor: pointer;
            padding: 1rem .1rem;
            border: 2px solid $buttonBackground;
            &.active {
              border: 2px solid $activeBorderColor;
            }
          }
        }
        .arrow {
          -webkit-font-smoothing: antialiased;
          font-family: "Helvetica Neue","Hiragino Sans GB","Microsoft YaHei","\9ED1\4F53",Arial,sans-serif;
          color: #222;
          font-size: 14px;
          list-style-type: none;
          text-align: center;
          line-height: 50px;
          position: absolute;
          left: 50%;
          margin-left: -6px;
          bottom: -5px;
          display: inline-block;
          width: 0;
          height: 0;
          border-width: 6px;
          border-style: dashed;
          border-color: transparent;
          border-bottom-width: 0;
          border-top-color: #e3e3e3 !important;
          border-top-style: solid;
        }
      }
    }
  }
}
</style>
