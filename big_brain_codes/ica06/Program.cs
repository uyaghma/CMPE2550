namespace ica06
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);
            builder.Services.AddControllers();
            var app = builder.Build();
            app.UseCors(x => x.AllowAnyMethod().AllowAnyHeader().SetIsOriginAllowed(origin => true));

            app.MapGet("/", () => "Welcome to Tim Hortons!");

            app.MapPost("/form-process", (Order order) =>
            {
                Random rand = new Random();
                int responseTime = rand.Next(5, 31);

                return new
                {
                    Name = order.name,
                    Location = order.location,
                    Items = order.item,
                    Quantity = order.quantity,
                    Payment = order.payment,
                    ResponseTime = responseTime
                };
            });

            app.Run();
        }
        record Order (string name, string location, string item, int quantity, string payment);
    }
}