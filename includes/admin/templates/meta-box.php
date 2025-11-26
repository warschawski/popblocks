<script>
  let time = (new Date()).getTime();
  
  window.PopBlocksConfig = <?php echo PopBlocks_Config::admin_settings(); ?>;
  
  <?php if ( ! empty( $popup_data ) ) : ?>
  window.PopUpsData = <?php echo $popup_data; ?>
  <?php else : ?>
  window.PopUpsData = {
    id: <?php echo $post->ID; ?>,
    triggers: [
      {
        id: 'id' + (new Date()).getTime() + 2,
        rules: [
          {
            id: 'id' + (new Date()).getTime(),
            parent: 'trigger',
            type: 'page_load',
            operator: 'delay',
            value: '',
            suffix: 'seconds',
          },
        ]
      }
    ],
    behaviors: [
      {
        id: 'id' + (new Date()).getTime() + 2,
        rules: [
          {
            id: 'id' + (new Date()).getTime(),
            parent: 'behavior',
            type: 'all',
            operator: 'none',
            value: '',
            suffix: 'none',
          },
        ]
      },
    ],
    options: {
      id: 'id' + (new Date()).getTime(),
      active: false,
      name: '',
      duration: {
        value: 0,
        unit: 'hours'
      }
    },
  };
  <?php endif; ?>
</script>

<div
  class="components-tab-panel"
  x-data="popupsMetaBox({
    color: null,
    trigger: '',
  })"
  x-on:tab-activated="onTabActivated"
>

  <div
    x-data="popupsTabs({
      activeTab: 'trigger'
    })"
  >
    <div class="components-tab-panel__tabs">
      <div class="components-tab-panel__border"></div>
      <button class="components-button components-tab-panel__tabs-item"
        data-active-item="true"
        role="tab"
        aria-selected="true"
        data-tab-id="trigger"
        type="button"
        x-on:click="setTab('trigger')"
        x-bind:class="{ 'is-active': isActiveTab('trigger') }"
      >
        <span>Triggers</span>
      </button>
      <button class="components-button components-tab-panel__tabs-item"
        data-active-item="true"
        role="tab"
        aria-selected="true"
        data-tab-id="behavior"
        type="button"
        x-on:click="setTab('behavior')"
        x-bind:class="{ 'is-active': isActiveTab('behavior') }"
      >
        <span>Behavior</span>
      </button>
      <button class="components-button components-tab-panel__tabs-item"
        data-active-item="true"
        role="tab"
        aria-selected="true"
        data-tab-id="options"
        type="button"
        x-on:click="setTab('options')"
        x-bind:class="{ 'is-active': isActiveTab('options') }"
      >
        <span>Options</span>
      </button>
    </div>

    <div id="trigger-tab"
      x-bind:class="{ 'hidden': !isActiveTab('trigger') }"
    >
      <p>Show this popup when</p>
      <div class="popup_triggers popup_controllers"
        x-data="ruleController({ groupTab: 'trigger' })"
      >
        <template x-for="(group, gIndex) in trGroups" :key="group.id">
          <x-component
            template="popup_conditionals"
            x-data="{ item: group }"
            styles="global"
          ></x-component>
        </template>
      </div>
    </div>

    <div id="behavior-tab"
      x-bind:class="{ 'hidden': !isActiveTab('behavior') }"
    >
      <p>Show this popup on</p>
      <div class="popup_triggers popup_controllers"
        x-data="ruleController({ groupTab: 'behavior' })"
      >
        <template x-for="(group, gIndex) in beGroups" :key="group.id">
          <x-component
            template="popup_conditionals"
            x-data="{ item: group }"
            styles="global"
          ></x-component>
        </template>
      </div>
    </div>

    <div
      id="options-tab"
      x-bind:class="{ 'hidden': !isActiveTab('options') }"
      x-data="PopUpsData.options"
    >
      <div
        class="options_tab_wrapper"
        
      >
        <div 
          class="flag_toggle_wrapper components-flex components-h-stack"
        >
          <span
            class="components-form-toggle"
            x-bind:class="{ 'is-checked': active }"
          >
            <input 
              class="components-form-toggle__input"
              type="checkbox"
              id="cookie_flag"
              name="cookie_flag"
              x-model="active"
            >
            <span class="components-form-toggle__track"></span>
            <span class="components-form-toggle__thumb"></span>
          </span>
          <label class="components-flex-item components-flex-block components-toggle-control__label" for="cookie_flag">
            Cookie Flag
          </label>
        </div>

        <div
          class="cookie_settings"
          x-show="active"
        >
          <div class="components-flex components-input-base">
            <div class="components-flex-item">
              <label 
                for="cookie-name" 
                class="components-truncate components-text components-input-control__label"
              >
                Cookie Name
              </label>
            </div>
            <div 
              class="components-input-control__container"
            >
              <input  
                id="cookie-name"
                class="components-input-control__input"
                type="text"
                x-model="name"
                x-bind:placeholder="name"
              />
              <div aria-hidden="true" class="components-input-control__backdrop"></div>
            </div>
          </div>

          <div class="components-flex components-input-base">

            <div class="components-flex-item">
              <label 
                for="cookie-time" 
                class="components-truncate components-text components-input-control__label"
              >
                Cookie Time
              </label>
            </div>
            <div 
              class="components-input-control__container"
            >
              <input  
                id="cookie-time"
                class="components-input-control__input"
                type="number"
                placeholder="0"
                x-model="duration.value"
              />
              <span 
                class="components-input-control__suffix"
              >
                <select 
                  class="components-unit-control__select" 
                  aria-label="Select unit"
                  x-model="duration.unit"
                >
                  <option value="hours">hours</option>
                  <option value="days">days</option>
                  <option value="weeks">weeks</option>
                  <option value="months">months</option>
                </select>
              </span>
              <div aria-hidden="true" class="components-input-control__backdrop"></div>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>
  
  <!-- <textarea name="popblocks_data" x-text="finalData()" style="width: 100%; height: 200px; margin-top: 40px;"></textarea> -->
  <textarea name="popblocks_data" x-text="finalData()" style="display: none;"></textarea>
</div>

<template id="popup_conditionals">
  <div>
    <p x-show="gIndex !== 0" class="or_group_separator">or</p>
    <template x-for="(rule, rIndex) in group.rules" :key="rule.id">
      <div class="popup_rule_group">
        <div x-show="rule.rule === 'or'" class="or_group_separator">or</div>
        <div class="popup_trigger_condition">
          <div class="popup_condition_wrapper">

            <div class="components-input-control__container">
              <select 
                class="components-select-control__input"
                x-model="rule.type"
                x-on:change="updateRuleType(rule)"
              >
                <template
                  x-for="option in $store.popups[rule.parent + 'Options']"
                >
                  <option
                    x-bind:value="option.value"
                    x-text="option.name"
                    x-bind:selected="option.value == rule.type"
                    x-bind:disabled="option.value === 'browser_location'"
                  ></option>
                </template>
              </select>
              <span class="components-input-control__suffix">
                <div data-wp-component="InputControlSuffixWrapper" class="components-input-control-suffix-wrapper">
                  <div>
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="18" height="18" aria-hidden="true" focusable="false">
                      <path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
                    </svg>
                  </div>
                </div>
              </span>
              <div aria-hidden="true" class="components-input-control__backdrop"></div>
            </div>

            <div 
              class="components-input-control__container"
              x-show="!['none', 'scroll'].includes(rule.operator)"
            >
              <select 
                class="components-select-control__input"
                x-model="rule.operator" 
              >
                <template
                  x-for="operatorOption in getOperatorOptions(rule)"
                >
                  <option
                    x-bind:value="operatorOption.id"
                    x-text="operatorOption.name"
                    x-bind:selected="operatorOption.id == rule.operator"
                  ></option>
                </template>
              </select>
              <span class="components-input-control__suffix">
                <div class="components-input-control-suffix-wrapper">
                  <div>
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="18" height="18" aria-hidden="true" focusable="false">
                      <path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
                    </svg>
                  </div>
                </div>
              </span>
              <div aria-hidden="true" class="components-input-control__backdrop"></div>
            </div>

            <template
              x-for="operatorOption in getOperatorOptions(rule)"
            >
              <div 
                class="components-input-control__container"
                x-show="rule.operator == operatorOption.id && rule.operator !== 'none'"
              >
                <input  
                  class="components-input-control__input"
                  type="text"
                  x-model="rule.value"
                  x-bind:placeholder="operatorOption.placeholder"
                />
                <span 
                  class="components-input-control__suffix"
                >
                  <div 
                    class="components-unit-control__unit-label"
                    x-text="rule.suffix" 
                    x-show="rule.suffix !== 'none' && activeTab !== 'behavior'"
                  ></div>
                </span>
                <div aria-hidden="true" class="components-input-control__backdrop"></div>
              </div>
            </template>

          </div>
          <button
            class="popup_condition_button and_button components-button is-primary"
            x-on:click="createRule(gIndex, rIndex)"
            x-show="activeTab !== 'trigger'"
          >
            and
          </button>
          <button 
            type="button" 
            aria-disabled="false" 
            class="popup_condition_remove components-button is-next-40px-default-size is-secondary is-destructive"\
            x-show="
              (gIndex == 0 && group.rules.length > 1) ||
              (gIndex == 0 && $data[groupName].length > 1) ||
              gIndex > 0
            "
            x-on:click="removeRule(gIndex, rIndex)"
          >
            Delete
          </button>
        </div>

      </div>
    </template>
    <div
      x-show="gIndex == $data[groupName].length - 1"
    >
      <p>or</p>
      <button class="popup_condition_button or_button components-button is-primary"
        x-on:click="createGroup()"
      >
        Add New Group
      </button>
    </div>
  </div>  
</template>