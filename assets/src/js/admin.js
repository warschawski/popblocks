import Alpine from 'alpinejs';
import component from 'alpinejs-component';

window.Alpine = Alpine;

Alpine.plugin(component)

Alpine.store('popups', {
  id: 0,
  triggers: [],
  behaviors: [],
  options: [],
  init: function() {
    console.log('PopBlocks Store: Init');

    // console.log(window.PopUpsData);

    if (typeof window.PopUpsData !== 'undefined') {

      if (typeof window.PopUpsData.id !== 'undefined') {
        this.id = window.PopUpsData.id;
        // console.log(this.id);
      }

      if (typeof window.PopUpsData.triggers !== 'undefined') {
        this.triggers = window.PopUpsData.triggers;
        // console.log(this.triggers[0]);
      }

      if (typeof window.PopUpsData.behaviors !== 'undefined') {
        this.behaviors = window.PopUpsData.behaviors;
        // console.log(this.behaviors);
      }

      if (typeof window.PopUpsData.options !== 'undefined') {
        this.options = window.PopUpsData.options;
        // console.log(this.options);
      }
    }

    this.behaviorOptions = window.PopBlocksConfig.behaviors;
    this.operatorOptions = window.PopBlocksConfig.operators;
    this.triggerOptions = window.PopBlocksConfig.triggers;
  },
});

//

window.popupsMetaBox = function(data) {

  function init() {
    console.log('PopBlocks Meta Box: Init');
  }

  function finalData() {
    return JSON.stringify({
      id: this.$store.popups.id,
      triggers: this.$store.popups.triggers,
      behaviors: this.$store.popups.behaviors,
      options: this.$store.popups.options,
    });
  }

  function onTabActivated(e) {
    console.log('PopBlocks Meta Box: Tab Activated (' + e.detail + ')');
  }

  return {
    init: init,
    finalData: finalData,
    //
    onTabActivated: onTabActivated,
  };
};

//

window.popupsTabs = function(data) {

  function setTab(tab) {
    this.activeTab = tab;

    this.$dispatch('tab-activated', this.activeTab);
  }

  function isActiveTab(tab) {
    return this.activeTab == tab;
  }

  return {
    activeTab: data.activeTab,
    setTab: setTab,
    isActiveTab: isActiveTab,
  }
};

//

window.popupsRuleController = function(data) {
  let parentGroup = data.groupTab;
  let groupName = parentGroup.substring(0,2) + 'Groups';
  let optionsName = parentGroup + 'Options';

  function init() {
    console.log('PopBlocks Rule Controller: Init (' + optionsName + ')');

    this.idGroups = this.$store.popups.triggers;
    this.trGroups = this.$store.popups.triggers;
    this.beGroups = this.$store.popups.behaviors;
    this.opGroups = this.$store.popups.options;

    this.$watch('$store.popups.id', (val) => {
      this.idGroups = this.$store.popups.id;
    });
    this.$watch('$store.popups.triggers', (val) => {
      this.trGroups = this.$store.popups.triggers;
    });
    this.$watch('$store.popups.behaviors', (val) => {
      this.beGroups = this.$store.popups.behaviors;
    });
    this.$watch('$store.popups.options', (val) => {
      this.opGroups = this.$store.popups.options;
    });

    // console.log(this.opGroups);
  }

  function getBaseRule(groupName) {
    return {
      id: 'pb' + (new Date()).getTime(),
      parent: groupName[0].rules[0].parent,
      type: groupName[0].rules[0].type,
      operator: groupName[0].rules[0].operator,
      suffix: groupName[0].rules[0].suffix,
      value: groupName[0].rules[0].value,
    };
  }

  function createGroup() {
    this[groupName].push({
      id: 'pb' + (new Date()).getTime(),
      rules: [
        getBaseRule(this[groupName]),
      ],
    });
  }

  function createRule(gIndex, rIndex) {
    this[groupName][gIndex].rules.splice(rIndex + 1, 0, getBaseRule(this[groupName]));

    // console.log(optionsName);
  }

  function removeRule(gIndex, rIndex) {
    this[groupName][gIndex].rules.splice(rIndex, 1);

    if (this[groupName][gIndex].rules.length == 0) {
      this[groupName].splice(gIndex, 1);
    }
  }

  function updateRuleType(rule) {
    let selectedTrigger = _.find(this.$store.popups[rule.parent + 'Options'], (trigger) => {
      return trigger.value == rule.type;
    });

    // console.log('this is the selected trigger:' + JSON.stringify(selectedTrigger))

    rule.operator = selectedTrigger.operators[0];
    rule.suffix = selectedTrigger.suffix;
  }

  function getOperatorOptions(rule) {
    let selectedTrigger = _.find(this.$store.popups[rule.parent + 'Options'], (trigger) => {
      return trigger.value == rule.type;
    });

    return _.filter(this.$store.popups.operatorOptions, (operator) => {
      return selectedTrigger.operators.indexOf(operator.id) > -1;
    });
  }

  return {
    idGroups: [],
    trGroups: [],
    beGroups: [],
    opGroups: [],
    //
    init: init,
    createGroup: createGroup,
    createRule: createRule,
    removeRule: removeRule,

    updateRuleType: updateRuleType,
    getOperatorOptions: getOperatorOptions,

    groupName: groupName,
  };
}

//

Alpine.start();