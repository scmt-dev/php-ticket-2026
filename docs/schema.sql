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

create table categories (
    id int primary key auto_increment,
    name varchar(50) unique not null,
    icon varchar(255) not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp
);

create table products (
    id int primary key auto_increment,
    name varchar(255) not null,
    description text not null,
    price decimal(10, 2) not null,
    category_id int null,
    image_url varchar(255) null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp,
    foreign key (category_id) references categories(id)
);

create table orders (
    id int primary key auto_increment,
    user_id int null,
    table_number int null,
    discount decimal(10, 2) default 0,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp,
    foreign key (user_id) references users(id)
);

create table order_items (
    id int primary key auto_increment,
    order_id int not null,
    product_id int not null,
    quantity int not null,
    created_at timestamp default current_timestamp,
    updated_at timestamp default current_timestamp,
    foreign key (order_id) references orders(id),
    foreign key (product_id) references products(id)
);