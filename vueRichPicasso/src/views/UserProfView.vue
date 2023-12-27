<template>
    <v-main class="main_container">
        <v-container>
            <v-row justify="center">
                <v-col cols="12" sm="8" md="6" lg="4">
                    <v-card class="elevation-12">
                        <v-toolbar color="green" dark flat>
                            <v-toolbar-title class="text-center w-100">YOUR ACCOUNT</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-text-field class="text_field_custom" label="First Name" prepend-icon="mdi-account"
                                type="text" v-model="firstName"></v-text-field>
                            <v-text-field class="text_field_custom" label="Last Name" prepend-icon="mdi-account" type="text"
                                v-model="lastName"></v-text-field>
                            <v-text-field class="text_field_custom" label="Email" prepend-icon="mdi-email" type="email"
                                v-model="email"></v-text-field>
                            <v-text-field class="text_field_custom" label="Password" prepend-icon="mdi-lock" type="password"
                                v-model="password"></v-text-field>
                            <v-btn color="green" dark class="update_password_btn" @click="updatePassword">Update
                                Password</v-btn>
                        </v-card-text>
                        <v-card-actions class="justify-center">
                            <v-btn color="red" dark width="100%" @click="logout">LOGOUT</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-main>
</template>


<script>
export default {
    data() {
        return {
            firstName: '',
            lastName: '',
            email: '',
            password: '',
        };
    },
    mounted() {
        // const userId = localStorage.getItem('userId');
        let id = localStorage.getItem('userId');
        console.log("userId : ", id)
        // getproduct
        const url = 'http://localhost:4208/zi_htdocs/RichRicasso/getuser';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(id),
        })
            .then(response => {
                if (response.status !== 200) {
                    console.log('Error: Non-200 status code');
                    return [];
                }
                // console.log(response.text())
                return response.json();
            }).then(data => {
                this.processFetchedData(data)
            })
            .catch(error => console.log(error));
    },
    methods: {
        logout() {
            localStorage.setItem('loggedIn', false);
            localStorage.setItem('userId', null);
            this.$router.push({ name: 'Account' });
            this.$router.go();
        },
        processFetchedData(data) {
            this.email = data.email;
            this.firstName = data.name;
            this.lastName = data.lastname;
            this.password = "";
        },
        updatePassword() {
            if (this.password) {
                console.log("Password Updated")
                let userInfo = {
                    password: this.password,
                    id: localStorage.getItem('userId')
                };
                console.log(userInfo)
                // getproduct
                const url = 'http://localhost:4208/zi_htdocs/RichRicasso/updateuser';
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(userInfo),
                })
                    .then(response => {
                        if (response.status !== 200) {
                            console.log('Error: Non-200 status code');
                            return [];
                        }
                        // console.log(response.text())
                        return response.json();
                    }).then(data => {
                        // console.log(data)
                        if(data){
                            // console.log(data)
                            alert("Password successfully Modified");
                        }else{
                            alert("Password not Modified");
                        }
                    })
                    .catch(error => console.log(error));
            }else{
                alert("Password field is empty.");
            }
        }

    },
};
</script>

<style scoped>
.text_field_custom {
    color: black;
}

v-container {
    height: 80%;
    width: 200%;
}

.main_container {
    height: 80%;
    width: 150%;
    margin-top: 5%;
}

.v-toolbar-title {
    width: 100%;
}

.update_password_btn {
    width: 100%;
    height: 100%;
    padding: 5%;
    font-size: 0.7rem;
}


.text-center {
    text-align: center;
}
</style>