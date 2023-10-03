-- insert ingredients:

INSERT INTO `Ingredient` (`ingredientId`, `ingredientName`) VALUES
(1, 'Sugar'),
(2, 'Salt'),
(3, 'Eggs'),
(4, 'Flour'),
(5, 'Water'),
(6, 'Milk'),
(7, 'Butter'),
(8, 'Baking powder'),
(9, 'Vanilla extract'),
(10, 'Olive oil'),
(11, 'Garlic'),
(12, 'Onion'),
(13, 'Tomato'),
(14, 'Pepper'),
(15, 'Cheese'),
(16, 'Chicken breast'),
(17, 'Ground beef'),
(18, 'Rice'),
(19, 'Pasta'),
(20, 'Bread crumbs'),
(21, 'Lemon'),
(22, 'Soy sauce'),
(23, 'Ginger'),
(24, 'Cinnamon'),
(25, 'Honey'),
(26, 'Nutmeg'),
(27, 'Brown sugar'),
(28, 'Yeast'),
(29, 'Cornstarch'),
(30, 'Chocolate chips'),
(31, 'Peanut butter'),
(32, 'Almonds'),
(33, 'Broccoli'),
(34, 'Spinach'),
(35, 'Carrots'),
(36, 'Potatoes'),
(37, 'Mushrooms'),
(38, 'Bell pepper'),
(39, 'Peas'),
(40, 'Corn'),
(41, 'Bacon'),
(42, 'Sausage'),
(43, 'Shrimp'),
(44, 'Salmon'),
(45, 'Tuna'),
(46, 'Beef steak'),
(47, 'Pork chops'),
(48, 'Turkey'),
(49, 'Oats'),
(50, 'Quinoa');


-- insert 1 admin and 1 user for testing:

insert into User (name, username, password) values ('Sam', 'Sam123', 'password123');
insert into User (name, username, password) values ('Jeff', 'Jeff123', 'password123');
insert into User (name, username, password) values ('Tim', 'Tim123', 'password123');

insert into User (name, username, password, role) values ('Josh', 'admin1', 'password1', 'admin'); -- admin

-- Chat GPT was used to generate the below insert statements given a prompt detailing the database structure.

-- Recipe 1
INSERT INTO Recipe (userId, recipeName, instructions, description, approved, imageName)
VALUES (1, 'Chocolate Chip Cookies', 'Preheat oven to 350 degrees F. In a large bowl, mix together the flour, baking powder, and salt. In a separate bowl, cream together the butter and sugars. Add the eggs and vanilla extract and mix well. Gradually add the dry ingredients to the wet ingredients, mixing until just combined. Stir in the chocolate chips. Drop spoonfuls of dough onto a greased baking sheet. Bake for 10-12 minutes or until the edges are lightly golden.', 'A classic dessert of dough made with flour, butter, sugar, eggs, vanilla, and chocolate chips, spooned onto a baking sheet and baked until lightly golden.', 1, 'cookie.jpg');

-- RecipeIngredients for Recipe 1
INSERT INTO RecipeIngredient (recipeId, ingredientId, quantity)
VALUES (1, 4, '2 cups'), (1, 7, '1 cup'), (1, 1, '1 cup'), (1, 8, '1 tsp'), (1, 9, '1 tsp'), (1, 30, '2 cups');

-- Recipe 2
INSERT INTO Recipe (userId, recipeName, instructions, description, approved, imageName)
VALUES (1, 'Spaghetti Carbonara', 'Cook pasta according to package instructions. In a large pan, cook bacon until crispy. Remove bacon from pan and set aside. In the same pan, cook garlic until fragrant. In a separate bowl, whisk together eggs and cheese. Drain pasta and return to pot. Add the egg mixture, bacon, and garlic to the pasta and toss to combine. Season with salt and pepper to taste.', 'An Italian dish of spaghetti tossed with a sauce made from eggs, cheese, bacon, garlic, salt, and pepper.', 1, 'carbonara.jpg');

-- RecipeIngredients for Recipe 2
INSERT INTO RecipeIngredient (recipeId, ingredientId, quantity)
VALUES (2, 19, '200 grams'), (2, 41, '100 grams'), (2, 11, '2 cloves'), (2, 3, '2'), (2, 15, '1 cup');

-- Recipe 3
INSERT INTO Recipe (userId, recipeName, instructions, description, approved, imageName)
VALUES (1, 'Lemon Garlic Roast Chicken', 'Preheat oven to 425 degrees F. Season chicken with salt and pepper. In a large ovenproof skillet, heat oil over medium heat. Add chicken and cook until browned on all sides. Remove chicken from skillet and set aside. In the same skillet, add garlic and cook until fragrant. Add lemon juice and chicken broth and bring to a boil. Return chicken to skillet and transfer to oven. Roast for 20-25 minutes or until chicken is cooked through.', 'Chicken seasoned with salt and pepper, browned in a skillet, then roasted in the oven with a sauce of lemon juice, chicken broth, and garlic.', 1, 'chicken.jpg');

-- RecipeIngredients for Recipe 3
INSERT INTO RecipeIngredient (recipeId, ingredientId, quantity)
VALUES (3, 16, '1 whole'), (3, 2, '1 tsp'), (3, 14, '1/2 tsp'), (3, 10, '2 tbsp'), (3, 11, '3 cloves'), (3, 21, '1/2 cup');

-- Recipe 4
INSERT INTO Recipe (userId, recipeName, instructions, description, approved, imageName)
VALUES (1, 'Vegetable Stir Fry', 'In a large wok or skillet, heat oil over medium heat. Add garlic and cook until fragrant. Add broccoli, bell pepper, carrots, and peas and cook until vegetables are tender-crisp. In a small bowl, mix together soy sauce, honey, and cornstarch. Add sauce to vegetables and cook until thickened. Serve over cooked rice.', 'A healthy dish of broccoli, bell pepper, carrots, and peas stir-fried with garlic and a sauce of soy sauce, honey, and cornstarch, served over rice.', 1, 'veg.jpg');

-- RecipeIngredients for Recipe 4
INSERT INTO RecipeIngredient (recipeId, ingredientId, quantity)
VALUES (4, 10, '2 tbsp'), (4, 11, '2 cloves'), (4, 33, '1 cup'), (4, 38, '1'), (4, 35, '2'), (4, 39, '1 cup'), (4, 22, '1/4 cup'), (4, 25, '2 tbsp'), (4, 29, '1 tbsp');

-- Recipe 5
INSERT INTO Recipe (userId, recipeName, instructions, description, approved, imageName)
VALUES (1, 'Classic Beef Stew', 'In a large pot, heat oil over medium heat. Add beef and cook until browned on all sides. Remove beef from pot and set aside. In the same pot, add onion and cook until tender. Add garlic and cook until fragrant. Return beef to pot and add broth, potatoes, carrots, and peas. Bring to a boil, then reduce heat and simmer for 1-2 hours or until beef is tender.', 'A hearty dish of beef browned and simmered with onions, garlic, broth, potatoes, carrots, and peas until tender.', 1, 'beef.jpg');

-- RecipeIngredients for Recipe 5
INSERT INTO RecipeIngredient (recipeId, ingredientId, quantity)
VALUES (5, 10, '2 tbsp'), (5, 17, '500 grams'), (5, 12, '1'), (5, 11, '3 cloves'), (5, 36, '3'), (5, 35, '2'), (5, 39, '1 cup');

-- Recipe 6
INSERT INTO Recipe (userId, recipeName, instructions, description, approved, imageName)
VALUES (1, 'Spicy Tuna Sushi Roll', 'Cook rice according to package instructions. In a small bowl, mix together tuna, mayonnaise, and sriracha. Place a sheet of nori on a bamboo sushi mat. Spread a thin layer of rice over the nori. Place a strip of tuna mixture along the center of the rice. Add cucumber and avocado slices on top of the tuna. Roll the sushi tightly using the bamboo mat. Cut into 8 pieces and serve with soy sauce.', 'A Japanese delicacy of cooked rice spread on nori, topped with a mixture of tuna, mayonnaise, and sriracha, cucumber, and avocado, rolled tightly, sliced, and served with soy sauce.', 1, 'sushi.jpg');

-- RecipeIngredients for Recipe 6
INSERT INTO RecipeIngredient (recipeId, ingredientId, quantity)
VALUES (6, 18, '2 cups'), (6, 45, '200 grams'), (6, 31, '2 tbsp'), (6, 22, '1 tbsp'), (6, 12, '1/2');


