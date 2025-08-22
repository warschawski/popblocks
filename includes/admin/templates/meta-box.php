<script>
  let time = (new Date()).getTime();
  
  window.PopBlocksConfig = <?php echo PopBlocks_Config::admin_settings(); ?>;
  
  <?php if ( ! empty( $popup_data ) ) : ?>
  window.PopUpsData = <?php echo $popup_data; ?>
  <?php else : ?>
  window.PopUpsData = {
    triggerGroups: [
      {
        id: 'id' + (new Date()).getTime() + 2,
        rules: [
          {
            id: 'id' + (new Date()).getTime(),
            parent: 'trigger',
            type: 'page_load',
            opperator: 'delay',
            value: '0',
          },
        ]
      },
    ],
    behaviorGroups: [
      {
        id: 'id' + (new Date()).getTime() + 2,
        rules: [
          {
            id: 'id' + (new Date()).getTime(),
            parent: 'behavior',
            type: 'page',
            opperator: 'equals',
            value: '0',
          },
        ]
      },
    ],
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
            x-data="{ item: group}"
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
            x-data="{ item: group}"
          ></x-component>
        </template>
      </div>
    </div>

    <div id="options-tab"
      x-bind:class="{ 'hidden': !isActiveTab('options') }"
    >
      <p>Additional settings</p>
      <div class="temp-options">
        <label for="start">Start date:</label>
        <input
          type="date"
          id="start"
          name="popup-start"
        />
        <input
          type="time"
          id="appointment"
          name="appointment"
        />
      </div>
      <div class="temp-options">
        <label for="end">End date:</label>
        <input
          type="date"
          id="end"
          name="popup-end"
        />
        <input
          type="time"
          id="appointment"
          name="appointment"
        />
      </div>
      <div class="temp-options">
        <label for="appointment">Hour range:</label>
        <input
          type="time"
          id="appointment"
          name="appointment"
        />
        <input
          type="time"
          id="appointment"
          name="appointment"
        />
      </div>
    </div>
  </div>
  
  <textarea name="popblocks_data" x-text="finalData()" style="width: 100%; height: 200px; margin-top: 40px;"></textarea>
</div>

<template id="popup_conditionals">
  <div>
    <style>
      .components-tab-panel {
        font-weight: 400;
        font-size: 13px;
        line-height: normal;
        transition: box-shadow .1s linear;
      }
      .components-tab-panel__tabs {
        position: relative;
      }
      .components-tab-panel__border {
        width: 100%;
        height: 1px;
        background-color: #ddd;
        position: absolute;
        bottom: 0;
        z-index: 0;
      }
      .popup_trigger_condition {
        position: relative;
        display: flex;
        align-items: stretch;
        margin: .5rem 0;
        gap: 0.25rem;
      }
      .popup_trigger_condition:hover .popup_condition_remove {
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .popup_condition_wrapper {
        display: flex;
        align-items: stretch;
        width: 90%;
        gap: 0.25rem;
      }
      .popup_condition_button, .popup_trigger_condition select, .popup_trigger_condition input, .popup_trigger_condition p {
        font-size: 13px;
        display: flex;
        align-items: center;
        width: calc(33.333333333333%);
        padding-left: 12px;
        padding-right: 12px;
        border: 1px solid #949494;
        border-radius: 2px;
        box-shadow: 0 0 0 #000 0;
        margin: 0;
        width: 100%;
      }
      .popup_condition_button.hidden, .popup_trigger_condition select.hidden, .popup_trigger_condition input.hidden, .popup_trigger_condition p.hidden {
        display: none;
      }
      .popup_trigger_group {
        display: flex;
        align-items: center;
        width: calc(33.333333333333%);
      }
      .popup_trigger_group select, .popup_trigger_group input, .popup_trigger_group p {
        width: 100%;
      }
      .popup_condition_button {
        width: 10%;
        height: auto;
        justify-content: center;
        cursor: pointer;
        min-width: 32px;
        padding: 4px;
        background: var(--wp-components-color-accent,var(--wp-admin-theme-color,#3858e9));
        color: var(--wp-components-color-accent-inverted,#fff);
        outline: 0;
        text-decoration: none;
        text-shadow: none;
        white-space: nowrap;
        border: 1px solid #3858e9;
      }
      .popup_condition_button:hover {
        color: var(--wp-components-color-accent,var(--wp-admin-theme-color,#3858e9));
        background-color: white;
        border: 1px solid #3858e9;
      }
      .popup_condition_button.or-button {
        width: fit-content;
        padding: 6px 12px;
      }
      .popup_condition_remove {
        position: absolute;
        top: 2.5px;
        right: calc(-12.5px);
        width: 25px;
        height: 25px;
        color: var(--wp-components-color-accent,var(--wp-admin-theme-color,#3858e9));
        border: 1px solid;
        border-radius: 100px;
        background-color: #fff;
        cursor: pointer;
        transform-origin: center;
        display: none;
      }
      .popup_condition_remove:hover {
        color: #dc143c;
        background-color: #ffe4e9;
      }
    </style>
    <p x-show="gIndex !== 0">or</p>
    <template x-for="(rule, rIndex) in group.rules" :key="rule.id">
      <div class="popup_rule_group">
        <div x-show="rule.rule === 'or'">or</div>
        <div class="popup_trigger_condition">
          <div class="popup_condition_wrapper">
            <select
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
                ></option>
              </template>
            </select>
            <select
              x-model="rule.opperator"
            >
              <template
                x-for="opperatorOption in getOpperatorOptions(rule)"
              >
                <option
                  x-bind:value="opperatorOption.id"
                  x-text="opperatorOption.name"
                  x-bind:selected="opperatorOption.id == rule.opperator"
                ></option>
              </template>
            </select>
            <template
              x-for="opperatorOption in getOpperatorOptions(rule)"
            >
              <input
                x-model="rule.value"
                x-bind:placeholder="opperatorOption.placeholder"
                x-show="rule.opperator == opperatorOption.id"
              ></input>
            </template>
          </div>
          <button
            class="popup_condition_button components-button"
            x-on:click="createRule(gIndex, rIndex)"
          >
            and
          </button>
          <button class="popup_condition_remove remove-button"
            x-show="
              (gIndex == 0 && group.rules.length > 1) ||
              (gIndex == 0 && $data[groupName].length > 1) ||
              gIndex > 0
            "
            x-on:click="removeRule(gIndex, rIndex)"
          >
            -
          </button>
        </div>

      </div>
    </template>
    <div
      x-show="gIndex == $data[groupName].length - 1"
    >
      <p>or</p>
      <button class="popup_condition_button or-button components-button"
        x-on:click="createGroup()"
      >
        Add New Group
      </button>
    </div>
  </div>  
</template>