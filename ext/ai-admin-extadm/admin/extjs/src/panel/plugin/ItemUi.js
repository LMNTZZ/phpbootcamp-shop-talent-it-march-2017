/*!
 * Copyright (c) Metaways Infosystems GmbH, 2011
 * LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */


Ext.ns('MShop.panel.plugin');

MShop.panel.plugin.ItemUi = Ext.extend(MShop.panel.AbstractItemUi, {

    siteidProperty : 'plugin.siteid',

    initComponent : function() {

        MShop.panel.AbstractListItemUi.prototype.setSiteCheck(this);

        this.items = [{
            xtype : 'tabpanel',
            activeTab : 0,
            border : false,
            itemId : 'MShop.panel.plugin.ItemUi',
            plugins : ['ux.itemregistry'],
            items : [{
                xtype : 'panel',
                title : MShop.I18n.dt('admin', 'Basic'),
                border : false,
                layout : 'hbox',
                layoutConfig : {
                    align : 'stretch'
                },
                itemId : 'MShop.panel.plugin.ItemUi.BasicPanel',
                plugins : ['ux.itemregistry'],
                defaults : {
                    bodyCssClass : this.readOnlyClass
                },
                items : [{
                    xtype : 'form',
                    title : MShop.I18n.dt('admin', 'Details'),
                    flex : 1,
                    ref : '../../mainForm',
                    autoScroll : true,
                    items : [{
                        xtype : 'fieldset',
                        style : 'padding-right: 25px;',
                        border : false,
                        labelAlign : 'top',
                        defaults : {
                            anchor : '100%'
                        },
                        items : [{
                            xtype : 'displayfield',
                            fieldLabel : MShop.I18n.dt('admin', 'ID'),
                            name : 'plugin.id'
                        }, {
                            xtype : 'MShop.elements.status.combo',
                            name : 'plugin.status'
                        }, {
                            xtype : 'combo',
                            fieldLabel : MShop.I18n.dt('admin', 'Type'),
                            name : 'plugin.typeid',
                            mode : 'local',
                            store : MShop.GlobalStoreMgr.get('Plugin_Type'),
                            displayField : 'plugin.type.label',
                            valueField : 'plugin.type.id',
                            forceSelection : true,
                            triggerAction : 'all',
                            allowBlank : false,
                            typeAhead : true,
                            listeners : {
                                'render' : {
                                    fn : function() {
                                        var record, index = this.store.find('plugin.type.code', 'order');
                                        if((record = this.store.getAt(index))) {
                                            this.setValue(record.id);
                                        }
                                    }
                                }
                            }
                        }, {
                            xtype : 'textfield',
                            fieldLabel : MShop.I18n.dt('admin', 'Provider'),
                            name : 'plugin.provider',
                            allowBlank : false,
                            maxLength : 255,
                            regex : /^[^ \v\t\r\n\f]+$/,
                            emptyText : MShop.I18n.dt('admin', 'Name of the plugin provider class (required)')
                        }, {
                            xtype : 'textfield',
                            fieldLabel : MShop.I18n.dt('admin', 'Label'),
                            name : 'plugin.label',
                            allowBlank : false,
                            maxLength : 255,
                            emptyText : MShop.I18n.dt('admin', 'Internal name (required)')
                        }, {
                            xtype : 'numberfield',
                            fieldLabel : MShop.I18n.dt('admin', 'Position'),
                            name : 'plugin.position',
                            allowDecimals : false,
                            allowBlank : false,
                            value : 0
                        }, {
                            xtype : 'displayfield',
                            fieldLabel : MShop.I18n.dt('admin', 'Created'),
                            name : 'plugin.ctime'
                        }, {
                            xtype : 'displayfield',
                            fieldLabel : MShop.I18n.dt('admin', 'Last modified'),
                            name : 'plugin.mtime'
                        }, {
                            xtype : 'displayfield',
                            fieldLabel : MShop.I18n.dt('admin', 'Editor'),
                            name : 'plugin.editor'
                        }]
                    }]
                }, {
                    xtype : 'MShop.panel.configui',
                    layout : 'fit',
                    flex : 1,
                    data : (this.record ? this.record.get('plugin.config') : {})
                }]
            }]
        }];

        this.store.on('beforesave', this.onBeforeSave, this);

        MShop.panel.plugin.ItemUi.superclass.initComponent.call(this);
    },


    afterRender : function() {
        var label = this.record ? this.record.data['plugin.label'] : MShop.I18n.dt('admin', 'new');
        //#: Plugin item panel title with attribute label ({0}) and site code ({1)}
        var string = MShop.I18n.dt('admin', 'Plugin: {0} ({1})');
        this.setTitle(String.format(string, label, MShop.config.site["locale.site.label"]));

        MShop.panel.plugin.ItemUi.superclass.afterRender.apply(this, arguments);
    },


    onBeforeSave : function(store, data) {
        MShop.panel.plugin.ItemUi.superclass.onBeforeSave.call(this, store, data, {
            configname : 'plugin.config'
        });
    }
});

Ext.reg('MShop.panel.plugin.itemui', MShop.panel.plugin.ItemUi);
