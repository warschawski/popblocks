<style title="popblocks-admin">
  <?php echo file_get_contents( $this->plugin->get_path() . 'assets/css/admin.css' ); ?>
</style>

<script>
  let time = (new Date()).getTime();
  let timeIndex = 0;

  function generate_id() {
    timeIndex++;

    return 'pb' + time + timeIndex;
  }

  window.PopBlocksConfig = <?php echo PopBlocks_Config::admin_settings(); ?>;

  <?php if ( ! empty( $popup_data ) ) : ?>
  window.PopUpsData = <?php echo json_encode( $popup_data ); ?>;
  <?php else : ?>
  window.PopUpsData = {
    id: <?php echo $post->ID; ?>,
    triggers: [
      {
        id: generate_id(),
        rules: [
          {
            id: generate_id(),
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
        id: generate_id(),
        rules: [
          {
            id: generate_id(),
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
      id: generate_id(),
      cookie: {
        active: false,
        name: '',
        duration: {
          value: 0,
          unit: 'hours',
        },
      },
    },
  };
  <?php endif; ?>
  console.log(window.PopUpsData);
</script>

<div
  class="popblocks-components-tab-panel"
  x-data="popupsMetaBox()"
  x-on:tab-activated="onTabActivated"
>
  <?php wp_nonce_field( basename( __FILE__ ), 'popblocks_nonce' ); ?>
  <div
    x-data="popupsTabs({
      activeTab: 'trigger'
    })"
  >
    <div class="popblocks-components-tab-panel__tabs">
      <div class="popblocks-components-tab-panel__border"></div>
      <button class="popblocks-components-button popblocks-components-tab-panel__tabs-item"
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
      <button class="popblocks-components-button popblocks-components-tab-panel__tabs-item"
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
      <button class="popblocks-components-button popblocks-components-tab-panel__tabs-item"
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
        x-data="popupsRuleController({ groupTab: 'trigger' })"
      >
        <template x-for="(group, gIndex) in trGroups" :key="group.id">
          <x-component
            template="popup_conditionals"
            x-data="{ item: group }"
            styles="popblocks-admin"
          ></x-component>
        </template>
      </div>
    </div>

    <div id="behavior-tab"
      x-bind:class="{ 'hidden': !isActiveTab('behavior') }"
    >
      <p>Show this popup on</p>
      <div class="popup_triggers popup_controllers"
        x-data="popupsRuleController({ groupTab: 'behavior' })"
      >
        <template x-for="(group, gIndex) in beGroups" :key="group.id">
          <x-component
            template="popup_conditionals"
            x-data="{ item: group }"
            styles="popblocks-admin"
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
        class="popblocks-options_tab_wrapper"
      >
        <div
          class="popblocks-flag_toggle_wrapper"
        >
          <span
            class="popblocks-components-form-toggle"
            x-bind:class="{ 'is-checked': cookie.active }"
          >
            <input
              class="popblocks-components-form-toggle__input"
              type="checkbox"
              id="cookie_control"
              name="cookie_control"
              x-model="cookie.active"
            >
            <span class="popblocks-components-form-toggle__track"></span>
            <span class="popblocks-components-form-toggle__thumb"></span>
          </span>
          <label class="popblocks-components-toggle-control__label" for="cookie_control">
            Cookie Control
          </label>
        </div>

        <div
          class="cookie_settings"
          x-show="cookie.active"
        >
          <div class="popblocks-components-input-base">
            <div>
              <label
                for="cookie_name"
                class="popblocks-components-input-control__label"
              >
                Cookie Name
              </label>
            </div>
            <div
              class="popblocks-components-input-control__container"
            >
              <input
                id="cookie_name"
                class="popblocks-components-input-control__input"
                type="text"
                x-model="cookie.name"
                x-bind:placeholder="cookie.name"
              />
              <div aria-hidden="true" class="popblocks-components-input-control__backdrop"></div>
            </div>
          </div>

          <div class="popblocks-components-input-base">
            <div>
              <label
                for="cookie_time"
                class="popblocks-components-input-control__label"
              >
                Cookie Time
              </label>
            </div>
            <div
              class="popblocks-components-input-control__container"
            >
              <input
                id="cookie_time"
                class="popblocks-components-input-control__input"
                type="number"
                placeholder="0"
                x-model="cookie.duration.value"
              />
              <span
                class="popblocks-components-input-control__suffix"
              >
                <select
                  class="popblocks-components-unit-control__select"
                  aria-label="Select unit"
                  x-model="cookie.duration.unit"
                >
                  <option value="hours">hours</option>
                  <option value="days">days</option>
                  <option value="weeks">weeks</option>
                  <option value="months">months</option>
                </select>
              </span>
              <div aria-hidden="true" class="popblocks-components-input-control__backdrop"></div>
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
    <p x-show="gIndex !== 0" class="popblocks-or_group_separator">or</p>
    <template x-for="(rule, rIndex) in group.rules" :key="rule.id">
      <div class="popblocks-popup_rule_group">
        <div x-show="rule.rule === 'or'" class="popblocks-or_group_separator">or</div>
        <div class="popblocks-popup_trigger_condition">
          <div class="popblocks-popup_condition_wrapper">

            <div class="popblocks-components-input-control__container">
              <select
                class="popblocks-components-select-control__input"
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
              <span class="popblocks-components-input-control__suffix">
                <div data-wp-component="InputControlSuffixWrapper" class="popblocks-components-input-control-suffix-wrapper">
                  <div>
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="18" height="18" aria-hidden="true" focusable="false">
                      <path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
                    </svg>
                  </div>
                </div>
              </span>
              <div aria-hidden="true" class="popblocks-components-input-control__backdrop"></div>
            </div>

            <div
              class="popblocks-components-input-control__container"
              x-show="!['none', 'scroll'].includes(rule.operator)"
            >
              <select
                class="popblocks-components-select-control__input"
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
              <span class="popblocks-components-input-control__suffix">
                <div class="popblocks-components-input-control-suffix-wrapper">
                  <div>
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="18" height="18" aria-hidden="true" focusable="false">
                      <path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
                    </svg>
                  </div>
                </div>
              </span>
              <div aria-hidden="true" class="popblocks-components-input-control__backdrop"></div>
            </div>

            <template
              x-for="operatorOption in getOperatorOptions(rule)"
            >
              <div
                class="popblocks-components-input-control__container"
                x-show="rule.operator == operatorOption.id && rule.operator !== 'none'"
              >
                <input
                  class="popblocks-components-input-control__input"
                  type="text"
                  x-model="rule.value"
                  x-bind:placeholder="operatorOption.placeholder"
                />
                <span
                  class="popblocks-components-input-control__suffix"
                >
                  <div
                    class="popblocks-components-unit-control__unit-label"
                    x-text="rule.suffix"
                    x-show="rule.suffix !== 'none' && activeTab !== 'behavior'"
                  ></div>
                </span>
                <div aria-hidden="true" class="popblocks-components-input-control__backdrop"></div>
              </div>
            </template>

          </div>
          <button
            class="popblocks-popup_condition_button popblocks-and_button popblocks-components-button popblocks-is-primary"
            x-on:click="createRule(gIndex, rIndex)"
            x-show="activeTab !== 'trigger'"
          >
            and
          </button>
          <button
            type="button"
            aria-disabled="false"
            class="popblocks-popup_condition_remove popblocks-components-button popblocks-is-secondary popblocks-is-destructive"
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
      <button class="popblocks-popup_condition_button popblocks-or_button popblocks-components-button popblocks-is-primary"
        x-on:click="createGroup()"
      >
        Add New Group
      </button>
    </div>
  </div>
</template>