create table users (
    id int primary key auto_increment,
    name varchar(50) not null,
    email varchar(255) unique not null,
    password varchar(255) not null,
    role varchar(20) default 'user',
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp
);

-- tickets table
create table tickets (
    id int primary key auto_increment,
    user_id int not null,
    title varchar(255) not null,
    description text not null,
    status varchar(20) default 'open',
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp,
    foreign key (user_id) references users(id)
);