using ica10.Models;
using ica10.RestaurantDB;

namespace ica10
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);
            builder.Services.AddControllers();
            var app = builder.Build();
            app.UseCors(x => x.AllowAnyMethod().AllowAnyHeader().SetIsOriginAllowed(origin => true));

            app.MapGet("/", () => "Hello World!");

            app.MapGet("/locations", () =>
            {
                using (var db = new Uyaghma1RestaurantsContext())
                {
                    var products = db.Locations.Select(x => new
                    {
                        x.Locationid,
                        x.LocationName
                    }).ToList();
                    return products;
                }
            });

            app.MapGet("/orders", (int id, int location) =>
            {
                using (var db = new Uyaghma1RestaurantsContext())
                {
                    var orders = db.Orders.Where(w => w.Locationid == location && w.Cid == id).Select(x => new
                    {
                        orderid = x.OrderId,
                        date = x.OrderDate,
                        payment = x.PaymentMethod,
                        item = x.Item.ItemName,
                        price = x.Item.ItemPrice,
                        count = x.ItemCount,
                        name = $"{x.CidNavigation.Fname} {x.CidNavigation.Lname}",
                        location = x.Location.LocationName
                    })
                    .ToList();
                    return orders;
                }
            });

            app.Run();
        }
    }
}