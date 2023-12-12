import 'package:cafe_library_services/model/beverage_model.dart';
import 'package:flutter/material.dart';

void main() {
  runApp(cafeBeverage());
}

class cafeBeverage extends StatelessWidget {
  int qty=1;
  List<int> _cart = [];

  void _addToCart() {
    setState(() {
      _cart.add(qty);
    });
  }
  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      // title: 'Library Beverage',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),

      home: Directionality(
        textDirection: TextDirection.ltr,
        child: Scaffold(
          appBar: AppBar(
            title: const Text('Order Beverage'),
            actions: [
              IconButton(
                icon: Icon(Icons.add_shopping_cart),
                tooltip: 'Add to cart',
                onPressed: _addToCart,
              ),
              Text(
                '$qty',
              ),
              // Text("Cafe Library",
              //   style: TextStyle(
              //     fontSize: 20,
              //     fontWeight: FontWeight.bold,
              //   ),),
            ],

          ),
          body: SingleChildScrollView(
            child: Column(
              children: [
                Padding(
                  padding: const EdgeInsets.all(12.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      TextFormField(
                        decoration: const InputDecoration(hintText: "Search ..."),
                      ),
                      const SizedBox(
                        height: 24.0,
                      ),
                      const Text(
                        "Categories",
                        style: TextStyle(
                          fontSize: 18.0,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ],
                  ),
                ),

                SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: categoriesList.map((e) => Padding(
                      padding: const EdgeInsets.only(left: 8.0),
                      child: Card(
                        color: Colors.white24,
                        elevation: 3.0,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(20.0),
                        ),
                        child: SizedBox(
                          height: 100,
                          width: 100,
                          child: Image.network(e),
                        ),
                      ),
                    ),
                    )
                        .toList(),
                  ),
                ),
                const SizedBox(
                  height: 12.0,
                ),
                const Padding(
                  padding: const EdgeInsets.only(top: 12.0, left: 12.0),
                  child: Text(
                    "Best Beverages",
                    style: TextStyle(
                      fontSize: 18.0,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
                const SizedBox(
                  height: 12.0,
                ),
                Padding(
                  padding: const EdgeInsets.all(12.0),
                  child: GridView.builder(
                    padding: EdgeInsets.zero,
                    shrinkWrap: true,
                    primary: false, //scroll beverage
                    itemCount: bestBeverages.length,
                    gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                      mainAxisSpacing: 20,
                      crossAxisSpacing: 20,
                      childAspectRatio: 0.7,
                      crossAxisCount: 2,
                    ),
                    itemBuilder: (ctx, index) {
                      BeverageModel singleBeverage = bestBeverages[index];
                      return Container(
                        decoration: BoxDecoration(
                          color: Colors.grey.withOpacity(0.3),

                          borderRadius: BorderRadius.circular(8.0),
                        ),
                        child: Column(
                          children: [
                            const SizedBox(
                              height: 18.0,
                            ),
                            Image.network(
                              singleBeverage.image,
                              height: 100,
                              width: 100,
                            ),
                            const SizedBox(
                              height: 12.0,
                            ),
                            Text(
                              singleBeverage.name,
                              style: const TextStyle(
                                fontSize: 18.0,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            Text("Price: \RM${singleBeverage.price}"),
                            const SizedBox(
                              height: 30.0,
                            ),

                            // choose for qty
                            Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: <Widget>[
                                IconButton(
                                  icon: Icon(Icons.remove),
                                  onPressed: () {
                                    setState(() {
                                      qty--;
                                    });
                                  },
                                ),
                                Text('$qty'),
                                IconButton(
                                  icon: Icon(Icons.add),
                                  onPressed: () {
                                    setState(() {
                                      qty++;
                                    });
                                  },
                                ),
                              ],
                            ),

                            // for add to cart beverage
                            //const Spacer(),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                ElevatedButton(
                                  onPressed: _addToCart,
                                  child: const Text("ADD TO CART"),
                                ),
                              ],
                            ),

                          ],
                        ),
                      );
                    },
                  ),
                ),
                const SizedBox(height: 12.0,),
              ],
            ),
          ),
        ),
      ),
    );
  }

  void setState(Null Function() param0) {}
}

List<String> categoriesList =[
  "https://insanelygoodrecipes.com/wp-content/uploads/2020/07/Cup-Of-Creamy-Coffee.png",
  "https://midwestniceblog.com/wp-content/uploads/2022/06/starbucks-copycat-vanilla-iced-coffee.jpg",
  "https://celebratingsweets.com/wp-content/uploads/2017/06/Espresso-Chip-Frappe-2.jpg",
  "https://www.evolvingtable.com/wp-content/uploads/2022/12/How-to-Make-Smoothie-1.jpg",
  "https://assets.tmecosys.com/image/upload/t_web767x639/img/recipe/ras/Assets/0A475B34-4E78-40D8-9F30-46223B7D77E7/Derivates/E55C7EA4-0E60-403F-B5DC-75EA358197BD.jpg",
  "https://hips.hearstapps.com/hmg-prod/images/types-of-bread-1666723473.jpg?crop=0.883785664578984xw:1xh;center,top&resize=1200:*",
];

List<BeverageModel> bestBeverages = [
  BeverageModel(
    image: "https://www.vegrecipesofindia.com/wp-content/uploads/2018/02/cafe-style-hot-coffee-recipe-1.jpg",
    id: "1",
    name: "Hot Coffee Latte",
    price: "8.50",
    description: "One of the best beverage, Hot Coffee",
    status: "pending",
  ),
  BeverageModel(
    image: "https://easyweeknightrecipes.com/wp-content/uploads/2020/08/Mocha-Iced-Coffee-5.jpg",
    id: "2",
    name: "Mocha Iced Coffee",
    price: "9.20",
    description: "One of the best beverage, Ice Coffee",
    status: "pending",
  ),
  BeverageModel(
    image: "https://www.julieseatsandtreats.com/wp-content/uploads/2022/05/Java-Chip-Frapp-Square.jpg",
    id: "3",
    name: "Java Chip Frappuccino",
    price: "14.30",
    description: "One of the best beverage, Frappe",
    status: "pending",
  ),
  BeverageModel(
    image: "https://i0.wp.com/www.peanutbutterandpeppers.com/wp-content/uploads/2013/02/Skinny-Strawberry-Mocha-Frappe-016a.jpg",
    id: "4",
    name: "Strawberry Smoothies",
    price: "11.20",
    description: "One of the best beverage, Smoothies",
    status: "pending",
  ),
  BeverageModel(
    image: "https://sugargeekshow.com/wp-content/uploads/2020/11/doctored_red_velvet_featured.jpg",
    id: "5",
    name: "Red Velvet Cake",
    price: "7.50",
    description: "One of the best beverage, Cake",
    status: "pending",
  ),
  BeverageModel(
    image: "https://www.simplejoy.com/wp-content/uploads/2021/04/Croissant-French-Toast-5.jpg",
    id: "6",
    name: "Croissant French Toast",
    price: "8.30",
    description: "One of the best beverage, Bread",
    status: "pending",
  ),
];