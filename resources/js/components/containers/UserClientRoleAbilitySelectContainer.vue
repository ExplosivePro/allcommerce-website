<template>
    <div class="ucra-container">
        <div class="client-select">
            <label>Assigned Client</label>
            <select2
                :items="clients"
                name="client_id"
                :allows-multiple="false"
                :allows-null="false"
                :selected="clientId"
                @item-selected="updateSelectedItem"
            ></select2>
        </div>

        <div class="role-select">
            <label v-if="showRolesDrop">Assigned Role</label>

            <select2 v-if="showRolesDrop"
                :items="availableRoles"
                name="role"
                :allows-multiple="false"
                :allows-null="false"
                :selected="selectedRole"
                @item-selected="updateSelectedRole"
            ></select2>
            <div class="spinny-loader" v-if="(!showRolesDrop) && (loading === true)">
                <div class="center-wrapper">
                    <i class="fad fa-galaxy fa-spin"></i>
                    <p>Loading...</p>
                </div>
            </div>
        </div>

        <div class="role-select" v-if="showAbilitiesSegment">
            <label>Abilities & Permissions</label>
            <checkbox-grid
                :items="availableAbilities"
                :loading="loading"
                @item-checked="processChecked"
            ></checkbox-grid>
        </div>

        <div class="hidden-values">
            <input type="hidden" name="client_id" :value="selectedClientId" />
            <input type="hidden" name="role" :value="selectedRole" />
        </div>
    </div>
</template>

<script>
    import Select2 from '../presenters/Select2FromArrayComponent'
    import CheckboxGrid from '../presenters/CheckboxGridComponent';
    export default {
        name: "UserClientRoleAbilitySelectContainer",
        components: {
            Select2,
            CheckboxGrid
        },
        props: ['clients', 'clientId', 'role'],
        watch: {
            selectedClientId(client_id) {
                if((client_id !== '') && (client_id !== 0)&& (client_id !== '0')) {
                    this.ajaxGetRolesForClient();
                }
                else {
                    this.showRolesDrop = false;
                    this.selectedRole = '';
                    this.availableRoles = [];
                }
            },
            selectedRole(role) {
                if((role !== '') && (role !== 0)&& (role !== '0')) {
                    this.ajaxGetAllAbilities();
                }
                else {
                    this.showAbilitiesSegment = false;
                    this.selectedAbilities = [];
                    this.availableAbilities = '';
                }
            }
        },
        data() {
            return {
                loading: false,
                selectedClientId: '',
                showRolesDrop: false,
                selectedRole: '',
                availableRoles: [],
                showAbilitiesSegment: false,
                selectedAbilities: [],
                availableAbilities: ''
            }
        },
        methods: {
            updateSelectedItem(uuid) {
                console.log('Updated Client ID to '+ uuid);
                this.selectedClientId = uuid;
            },
            updateSelectedRole(role) {
                console.log('Updated Role to '+ role);
                this.selectedRole = role;
            },
            ajaxGetRolesForClient() {
                if ((this.selectedClientId !== '0') && (this.selectedClientId !== 0) && ((this.selectedClientId !== ''))) {
                    let _this = this;
                    this.loading = true;
                    _this.showRolesDrop = false;
                    _this.showAbilitiesSegment = false;

                    $.ajax({
                        url: '/access/roles/'+_this.selectedClientId,
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            console.log('Returned Data - ', data);
                            if(data['success']) {
                                if(_this.role in data['roles']) {
                                    _this.selectedRole = _this.role;
                                }

                                _this.availableRoles = data['roles'];
                                _this.showRolesDrop = true;
                            }
                            else {
                                _this.availableRoles = [];
                                _this.showRolesDrop = false;
                            }
                            _this.loading = false;
                            //_this.ajaxGetEnabledAbilities();
                        },
                        error: function (e) {
                            console.log('Error contacting server - ',e);
                            _this.loading = false;
                        }
                    });
                }
            },
            ajaxGetAllAbilities() {
                let _this = this;
                this.loading = true;
                this.showAbilitiesSegment = true;

                $.ajax({
                    url: '/abilities?client_id='+this.clientId,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log('Returned Data - ', data);
                        if(data['success']) {
                            _this.availableAbilities = data['abilities'];

                            for(let name in _this.availableAbilities) {
                                let title = _this.availableAbilities[name];
                                _this.availableAbilities[name] = {
                                    title: title,
                                    disabled: true,
                                    checked: false
                                }
                            }
                            _this.ajaxGetEnabledAbilities();
                        }
                        else {
                            _this.showAbilitiesSegment = false;
                        }
                    },
                    error: function (e) {
                        console.log('Error contacting server - ',e);
                        _this.loading = false;
                        _this.showAbilitiesSegment = false;
                    }
                });
            },
            ajaxGetEnabledAbilities() {
                let _this = this;
                this.loading = true;
                let role = this.selectedRole;

                $.ajax({
                    url: '/abilities/'+role,
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log('Enabled Returned Data - ', data);
                        if(data['success']) {
                            for(let idx in data['assigned']) {
                                let name = data['assigned'][idx]['name'];
                                if(name in _this.availableAbilities) {
                                    _this.availableAbilities[name].checked = true;
                                    _this.processChecked(_this.availableAbilities[name], name);
                                }
                            }

                            _this.showAbilitiesSegment = true;
                        }

                        _this.loading = false;
                    },
                    error: function (e) {
                        console.log('Error contacting server - ',e);
                        _this.loading = false;
                    }
                });
            },
            processChecked(item, name) {
                console.log('Item Toggled - '+name, item);
                this.availableAbilities[name] = item;

                if(item.checked) {
                    this.selectedAbilities.push(name);
                }
                else {
                    for(let idx in this.selectedAbilities) {
                        if(this.selectedAbilities[idx] === name) {
                            this.selectedAbilities.splice(idx, 1);
                        }
                    }
                }

                console.log('Updated selectedAbilities', this.selectedAbilities);
            }
        },
        mounted() {
            this.selectedClientId = this.clientId;
            console.log('UserClientRoleAbilitySelectContainer mounted!', this.role);
        }
    }
</script>

<style scoped>
    @media screen {
        .spinny-loader {
            text-align: center;
        }

        .spinny-loader i {
            font-size: 3em;
        }

        .spinny-loader p {
            margin-top: 0.5em;
            font-weight: thin;
            font-size: 1.25em;
            text-transform: uppercase;
            letter-spacing: 0.1em
        }

        .role-select {
            margin-top: 1.5em;
        }
    }
</style>
